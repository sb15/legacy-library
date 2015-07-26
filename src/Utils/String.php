<?php

//------------------------------------------------------------------------------
//MaxMatching - максимальная длина подстроки (достаточно 3-4)
//strInputMatching - сравниваемая строка
//strInputStandart - строка-образец

// Сравнивание без учета регистра
// if IndistinctMatching(4, "поисковая строка", "оригинальная строка  - эталон") > 40 then ...
type
TRetCount = packed record
lngSubRows: Word;
lngCountLike: Word;
end;

//------------------------------------------------------------------------------

function Matching(StrInputA: WideString;
StrInputB: WideString;
lngLen: Integer): TRetCount;
var
TempRet: TRetCount;
PosStrB: Integer;
PosStrA: Integer;
StrA: WideString;
StrB: WideString;
StrTempA: WideString;
StrTempB: WideString;
begin
StrA := string(StrInputA);
StrB := string(StrInputB);

for PosStrA := 1 to Length(strA) - lngLen + 1 do
    begin
    StrTempA := System.Copy(strA, PosStrA, lngLen);

PosStrB := 1;
for PosStrB := 1 to Length(strB) - lngLen + 1 do
    begin
    StrTempB := System.Copy(strB, PosStrB, lngLen);
if SysUtils.AnsiCompareText(StrTempA, StrTempB) = 0 then
begin
Inc(TempRet.lngCountLike);
break;
end;
end;

Inc(TempRet.lngSubRows);
end; // PosStrA

Matching.lngCountLike := TempRet.lngCountLike;
Matching.lngSubRows := TempRet.lngSubRows;
end; { function }

//------------------------------------------------------------------------------

function IndistinctMatching(MaxMatching: Integer;
strInputMatching: WideString;
strInputStandart: WideString): Integer;
var
gret: TRetCount;
tret: TRetCount;
lngCurLen: Integer; //текущая длина подстроки
begin
//если не передан какой-либо параметр, то выход
if (MaxMatching = 0) or (Length(strInputMatching) = 0) or
(Length(strInputStandart) = 0) then
begin
IndistinctMatching := 0;
exit;
end;

gret.lngCountLike := 0;
gret.lngSubRows := 0;
// Цикл прохода по длине сравниваемой фразы
for lngCurLen := 1 to MaxMatching do
    begin
    //Сравниваем строку A со строкой B
tret := Matching(strInputMatching, strInputStandart, lngCurLen);
gret.lngCountLike := gret.lngCountLike + tret.lngCountLike;
gret.lngSubRows := gret.lngSubRows + tret.lngSubRows;
//Сравниваем строку B со строкой A
tret := Matching(strInputStandart, strInputMatching, lngCurLen);
gret.lngCountLike := gret.lngCountLike + tret.lngCountLike;
gret.lngSubRows := gret.lngSubRows + tret.lngSubRows;
end;

if gret.lngSubRows = 0 then
begin
IndistinctMatching := 0;
exit;
end;

IndistinctMatching := Trunc((gret.lngCountLike / gret.lngSubRows) * 100);
end;

delphi world