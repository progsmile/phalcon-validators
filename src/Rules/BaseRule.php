<?php
namespace Progsmile\Validator\Rules;

abstract class BaseRule
{
    const CONFIG_ALL         = 'all';
    const CONFIG_DATA        = 'data';
    const CONFIG_ORM         = 'orm';
    const CONFIG_FIELD_RULES = 'fieldRules';

    private $config;

    protected $params;

    public function __construct($config)
    {
        $this->config = $config;
    }

    protected function getConfig($type = self::CONFIG_ALL)
    {
        if ($type == self::CONFIG_ALL){
            return $this->config;
        }

        return isset($this->config[$type]) ? $this->config[$type] : [];
    }

    protected function hasRule($rule)
    {
        if(!$rule){
            return false;
        }

        return strpos($this->getConfig(self::CONFIG_FIELD_RULES), $rule) !== false;
    }

    /**
     * Check if variable is not required - to prevent error messages from another validators
     *
     * @param string $type | 'var' or 'file'
     * @return bool
     */
    protected function isNotRequiredAndEmpty($type = 'var')
    {
        $condition = false;

        if ($type == 'var'){
            $condition = !$this->params[1];

        } elseif ($type == 'file') {

            $fieldsName = $this->params[0];

            //when file field is not required and empty
            $condition = isset($_FILES[$fieldsName]['name']) && $_FILES[$fieldsName]['name'] == '';
        }

        return !$this->hasRule('required') && $condition;
    }

    public function setParams($params)
    {
        $this->params = $params;

        return $this;
    }

    /**
     * Will the process to check if it is valid or not
     *
     * @return boolean Return the result if valid or not
     */
    public abstract function isValid();

    /**
     * Get the message if error occured
     *
     * @return string
     */
    public abstract function getMessage();

}