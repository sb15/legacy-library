<?php

namespace Models;

class FilesGenerator
{
    public static function getNameMany($name)
    {
        $result = $name;
        $lastWord = substr($name, -1, 1);

        switch ($lastWord) {
            case 's':
                $result .= 'es';
            break;
            case 'y':
                $result = substr($result, 0, strlen($result) - 1) . 'ies';
            break;
            default:
            $result .= 's';
        }
        return $result;
    }

    public function __construct($modelsFolder, $di, $namespaceName = 'Model')
    {
        $filter = new \Zend_Filter_Word_UnderscoreToCamelCase();

        $phalconGenerator = new \Models\Phalcon\Generator($di);
        $tables = $phalconGenerator->getTablesData();

        if (!is_dir($modelsFolder . "\\" . 'Generated')) {
            mkdir($modelsFolder . "\\" . 'Generated', 0777, true);
        }

        $namespace = $namespaceName;
        $namespaceGenerated = $namespace ."\\Generated";

        foreach ($tables as $table) {

            $cg = new ClassGenerator($table['model'], 'Common', $namespaceGenerated);

            $fieldList = array();
            foreach ($table['columns'] as $field) {
                $cg->addProperty($field, 'protected');
                $fieldList[] = lcfirst($filter->filter($field));
            }

            $cg->addProperty('fieldList', 'protected', $fieldList);

            $content = "        return \"{$table['name']}\";";
            $cg->addMethod('getSource', 'public', array(), $content);


            $content = "";

            if (isset($table['ref_one_to_many'])) {
                foreach ($table['ref_one_to_many'] as $ref) {
                    $content .= "        \$this->belongsTo(\"{$ref['column']}\", '{$namespaceName}\\{$ref['model']}', \"{$ref['ref_column']}\", array('alias' => '{$ref['model']}'));\n";

                    $content2 = "        return \$this->getRelated('{$ref['model']}', \$parameters);";
                    $cg->addMethod('get' . $ref['model'], 'public', array(array('name' => 'parameters', 'default' => 'null')), $content2, "@return \\{$namespaceName}\\{$ref['model']}");

                }
            }

            if (isset($table['ref_many_to_one'])) {
                foreach ($table['ref_many_to_one'] as $ref) {
                    $content .= "        \$this->hasMany(\"{$ref['column']}\", '{$namespaceName}\\{$ref['model']}', \"{$ref['ref_column']}\", array('alias' => '{$ref['model']}'));\n";

                    $content2 = "        return \$this->getRelated('{$ref['model']}', \$parameters);";
                    $cg->addMethod('get' . self::getNameMany($ref['model']), 'public', array(array('name' => 'parameters', 'default' => 'null')), $content2, "@return \\{$namespaceName}\\{$ref['model']}[] ");
					
					$varName = lcfirst($ref['model']);
					$content3 = "        \$this->{$ref['model']} = array(\${$varName});";
                    $cg->addMethod('add' . $ref['model'], 'public', array(array('name' => $varName, 'type' => "\\{$namespaceName}\\{$ref['model']}")), $content3, "@return void");
                }
            }

            if (isset($table['ref_one_to_one'])) {
                foreach ($table['ref_one_to_one'] as $ref) {
                    $content .= "        \$this->hasOne(\"{$ref['column']}\", '{$namespaceName}\\{$ref['model']}', \"{$ref['ref_column']}\", array('alias' => '{$ref['model']}'));\n";

                    $content2 = "        return \$this->getRelated('{$ref['model']}', \$parameters);";
                    $cg->addMethod('get' . $ref['model'], 'public', array(array('name' => 'parameters', 'default' => 'null')), $content2, "@return \\{$namespaceName}\\{$ref['model']}");

                }
            }

            if ($content) {
                $cg->addMethod('initialize', 'public', array(), $content);
            }


            $content = "        return parent::findFirst(\$parameters);";
            $cg->addMethod('findFirst', 'public static', array(array('name' => 'parameters', 'default' => 'null')), $content, "@return \\{$namespaceName}\\{$table['model']}");

            $content = "        return parent::find(\$parameters);";
            $cg->addMethod('find', 'public static', array(array('name' => 'parameters', 'default' => 'null')), $content, "@return \\{$namespaceName}\\{$table['model']}[]");

			$content = "        return new \\{$namespaceName}\\{$table['model']}();";
            $cg->addMethod('get', 'public static', array(), $content, "@return \\{$namespaceName}\\{$table['model']}");
			
            $cg->generateGettersAndSetters();

            file_put_contents($modelsFolder . "\\Generated\\" . $table['model'] . '.php', $cg);

            if (!is_file($modelsFolder . "\\" . $table['model'] . '.php')) {
                $cg = new ClassGenerator($table['model'], "\\{$namespaceGenerated}\\{$table['model']}", 'Model');
                file_put_contents($modelsFolder . "\\" . $table['model'] . '.php', $cg);
            }

        }

        $cg = new ClassGenerator("Common", "\\Phalcon\\Mvc\\Model", "{$namespaceGenerated}");
		$content = "        \$query = new \\Phalcon\\Mvc\\Model\\Query(\$phql);\n";
		$content .= "        \$query->setDI(\$this->getDI());\n";
		$content .= "        return \$query;\n";       
		$cg->addMethod('getQuery', 'public', array(array('name' => 'phql', 'default' => '')), $content, "@return \\Phalcon\\Mvc\\Model\\Query");

        $cg->addProperty('fieldList', 'protected', 'array()');

        $content = "        \$filter = new \\Zend_Filter_Word_UnderscoreToCamelCase();\n";
        $content .= "        foreach (\$data as \$k => \$v) {\n";
        $content .= "            if (in_array(\$k, \$this->fieldList)) {\n";
        $content .= "                \$fn = \"set\" . ucfirst(\$filter->filter(\$k));\n";
        $content .= "                \$this->\$fn(\$v);\n";
        $content .= "            }\n";
        $content .= "        }\n";

        $cg->addMethod('populate', 'public', array(array('name' => 'data', 'default' => 'array()')), $content, "@return null");

        file_put_contents($modelsFolder . "\\Generated\\Common.php", $cg);


    }
}