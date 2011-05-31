<?php
class Doctrine_Template_Listener_Blameable extends Doctrine_Record_Listener
{
    /**
     * Array of timestampable options
     *
     * @var string
     */
    protected $_options = array();
    
    /**
     * The default value of the blameVar if one isn't available
     * 
     * @var string
     */
    protected $_default = null;
    
    
    /**
     * __construct
     *
     * @param string $options 
     * @return void
     */
    public function __construct(array $options)
    {
        $this->_options = $options;
 
    }

    /**
     * Set the created and updated Blameable columns when a record is inserted
     *
     * @param Doctrine_Event $event
     * @return void
     */
    public function preInsert(Doctrine_Event $event)
    {
		
		$ident = $this->getUserIdentity();
		
		
        if (!$this->_options['columns']['created']['disabled']) {
            $createdName = $event->getInvoker()->getTable()->getFieldName($this->_options['columns']['created']['name']);
            $event->getInvoker()->$createdName = $ident;
        	}

        if ( ! $this->_options['columns']['updated']['disabled'] && $this->_options['columns']['updated']['onInsert']) {
            $updatedName = $event->getInvoker()->getTable()->getFieldName($this->_options['columns']['updated']['name']);
            $event->getInvoker()->$updatedName = $this->getUserIdentity();
        	}
		
    }

    /**
     * Set updated Blameable column when a record is updated
     *
     * @param Doctrine_Event $evet
     * @return void
     */
    public function preUpdate(Doctrine_Event $event)
    {
        if ( ! $this->_options['columns']['updated']['disabled']) {
            $updatedName = $event->getInvoker()->getTable()->getFieldName($this->_options['columns']['updated']['name']);
            $modified = $event->getInvoker()->getModified();
            if ( ! isset($modified[$updatedName])) {
                $event->getInvoker()->$updatedName = $this->getUserIdentity();
            }
        }
    }

    /**
     * Set the updated field for dql update queries
     *
     * @param Doctrine_Event $evet
     * @return void
     */
    public function preDqlUpdate(Doctrine_Event $event)
    {
        if ( ! $this->_options['columns']['updated']['disabled']) {
            $params = $event->getParams();
            $updatedName = $event->getInvoker()->getTable()->getFieldName($this->_options['columns']['updated']['name']);
            $field = $params['alias'] . '.' . $updatedName;
            $query = $event->getQuery();

            if ( ! $query->contains($field)) {
                $query->set($field, '?', $this->getUserIdentity());
            }
        }
    }

    /**
     * Gets the users identity from the $blameVar index of either the $_SESSION
     * or $GLOBALS array; OR use the default value
     *
     * @return void
     */
    public function getUserIdentity()
    {
        
		
        $ident = isset($GLOBALS[$this->_options['blameVar']]) ? $GLOBALS[$this->_options['blameVar']] : null;

		
		
        if (is_null($ident) && $this->_options['default'] !== false) {
            if (is_null($this->_default)) {
            
                /*
                 * Try to parse the default value as a dql string, if that fails
                 * set the default value equal to the literal value of the string
                 */
    
                try {
                    $default = Doctrine_Query::create()
                        ->parseDqlQuery($this->_options['default'])
                        ->fetchOne($this->_options['params']);
    
                    $this->_default = $default[$this->_options['blameVar']];
                } catch (Doctrine_Query_Tokenizer_Exception $e) {
                    $this->_default = $this->_options['default'];
                }
            }
            $ident = $this->_default;
        }
        
        return $ident;    
        
    }
}
