<?php

class Grabber_PlaceInfo
{
    // strip tags , trim

    protected $_name = null;
    protected $_type = null;
    protected $_logoUrl = null;
    protected $_photos = null;
    protected $_address = null;
    protected $_phones = null;
    protected $_emails = null;
    protected $_site = null;
    protected $_location = null;
    protected $_metro = null;

    protected $_worktime = null;
    protected $_additional = null;

    protected $_source = null;

    /**
     * @return the $_name
     */
    public function getName ()
    {
        return $this->_name;
    }

	/**
     * @return the $_type
     */
    public function getType ()
    {
        return $this->_type;
    }

	/**
     * @return the $_logoUrl
     */
    public function getLogoUrl ()
    {
        return $this->_logoUrl;
    }

	/**
     * @return the $_address
     */
    public function getAddress ()
    {
        return $this->_address;
    }

	/**
     * @return the $_phones
     */
    public function getPhones ()
    {
        return $this->_phones;
    }

    public function getPhotos()
    {
        return $this->_photos;
    }

	/**
     * @return the $_emails
     */
    public function getEmails ()
    {
        return $this->_emails;
    }

	/**
     * @return the $_site
     */
    public function getSite ()
    {
        return $this->_site;
    }

	/**
     * @return the $_location
     */
    public function getLocation ()
    {
        return $this->_location;
    }

	/**
     * @return the $_metro
     */
    public function getMetro ()
    {
        return $this->_metro;
    }

	/**
     * @return the $_worktime
     */
    public function getWorktime ()
    {
        return $this->_worktime;
    }

	/**
     * @return the $_additional
     */
    public function getAdditional ()
    {
        return $this->_additional;
    }

	/**
     * @return the $_source
     */
    public function getSource ()
    {
        return $this->_source;
    }


	public function filter($text)
    {
        $result = trim(strip_tags($text));
        return !empty($result) ? $result : null;
    }

    public function setName($text)
    {
        $this->_name = $this->filter($text);
    }

    public function setType($text)
    {
        $this->_type = $this->filter($text);
    }

    public function setLogoUrl($url)
    {
        // check
        $this->_logoUrl = $url;
    }

    public function setAddress($text)
    {
        $this->_address = $this->filter($text);
    }

    /**
     * @param NULL $_metro
     */
    public function setMetro ($_metro)
    {
        $this->_metro = $_metro;
    }

	/**
     * @param NULL $_worktime
     */
    public function setWorktime ($_worktime)
    {
        $this->_worktime = $_worktime;
    }

	/**
     * @param NULL $_additional
     */
    public function setAdditional ($_additional)
    {
        $this->_additional = $_additional;
    }

	public function setPhones($text)
    {
        $this->_phones = $this->filter($text);
    }

    public function setEmails($text)
    {
        $this->_emails = $this->filter($text);
    }

    public function setSite($text)
    {
        $this->_site = $this->filter($text);
    }

    public function setSource($url)
    {
        $this->_source = $url;
    }

    public function setLocation($text)
    {
        $this->_location = $text;
    }

}