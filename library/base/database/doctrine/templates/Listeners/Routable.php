<?php

class Doctrine_Template_Listener_Routable extends Doctrine_Record_Listener
{
    /**
     * Array of sluggable options
     *
     * @var string
     */
    protected $_options = array();

    /**
     * __construct
     *
     * @param string $array
     * @return void
     */
    public function __construct(array $options)
    {
			
        $this->_options = $options;
    }

    /**
     * Set the slug value automatically when a record is inserted
     *
     * @param Doctrine_Event $event
     * @return void
     */
	 
	 
	public function indexInsert($record){
		$table 		= $record->getTable();
		$table_name = $table->getTableName();
		$section 	= Doctrine_Core::getTable("RoutesSections")->findOneByForeignTable($table_name);
		
		$index = new RoutesIndex();
		$index->section_id = $section->id;
		$index->foreign_id = $record->id;
		$index->slug = $record->slug;
		$index->save();
		}
		
	public function indexUpdate($slug, $record){
		$table 		= $record->getTable();
		$table_name = $table->getTableName();
		
		$index = Doctrine_Core::getTable('RoutesIndex')->findOneBySlug($record->slug);
		if($index){
			$index->slug = $slug;
			$index->save();
		}else{
			$this->indexInsert($record);		
			}
		}
	 
    public function preInsert(Doctrine_Event $event)
    {
        $record = $event->getInvoker();
        $name = $record->getTable()->getFieldName($this->_options['name']);

        if ( ! $record->$name) {
            $slug = $this->buildSlugFromFields($record);
			$slug = call_user_func_array($this->_options['postProcess'], array($slug, $record));
			$record->$name = $slug;
        	}
    }
	
	public function postInsert(Doctrine_Event $event){
		$record = $event->getInvoker();
		$name   = $record->getTable()->getFieldName($this->_options['name']);
		
		if ($record->$name) {
			$this->indexInsert($record);
			}
		}

    /**
     * Set the slug value automatically when a record is updated if the options are configured
     * to allow it
     *
     * @param Doctrine_Event $event
     * @return void
     */
		
    public function preUpdate(Doctrine_Event $event){
		
		
		if(isset($record['status']) && $record['status'] == 'published') return;
		
        if (false !== $this->_options['unique']) {
            $record = $event->getInvoker();
            $name = $record->getTable()->getFieldName($this->_options['name']);
			
			
            
			if ( ! $record->$name || (
                false !== $this->_options['canUpdate'] &&
                ! array_key_exists($name, $record->getModified())
            )) {
				
				$slug = $this->buildSlugFromFields($record);
				
				$slug = call_user_func_array($this->_options['postProcess'], array($slug, $record));
				
				$this->indexUpdate($slug, $record);
                $record->$name = $slug;
				
            } else if ( ! empty($record->$name) &&
                false !== $this->_options['canUpdate'] &&
                array_key_exists($name, $record->getModified()
            )) {
				$slug = $this->buildSlugFromSlugField($record);
				$slug = call_user_func_array($this->_options['postProcess'], array($slug, $record));
				$this->indexUpdate($slug, $record);
                $record->$name = $slug;
            }
        }
    }

    /**
     * Generate the slug for a given Doctrine_Record based on the configured options
     *
     * @param Doctrine_Record $record
     * @return string $slug
     */
    protected function buildSlugFromFields($record)
    {
        if (empty($this->_options['fields'])) {
            if (is_callable($this->_options['provider'])) {
            	$value = call_user_func($this->_options['provider'], $record);
            } else if (method_exists($record, 'getUniqueSlug')) {
                $value = $record->getUniqueSlug($record);
            } else {
                $value = (string) $record;
            }
        } else {
            $value = '';
            foreach ($this->_options['fields'] as $field) {
                $value .= $record->$field . ' ';
            }
            $value = substr($value, 0, -1);
        }

    	if ($this->_options['unique'] === true) {
				
    		return $this->getUniqueSlug($record, $value);
    	}

        return call_user_func_array($this->_options['builder'], array($value, $record));
    }

    /**
     * Generate the slug for a given Doctrine_Record slug field
     *
     * @param Doctrine_Record $record
     * @return string $slug
     */
    protected function buildSlugFromSlugField($record)
    {
        $name = $record->getTable()->getFieldName($this->_options['name']);
        $value = $record->$name;

        if ($this->_options['unique'] === true) {
            return $this->getUniqueSlug($record, $value);
        }

        return call_user_func_array($this->_options['builder'], array($value, $record));
    }

    /**
     * Creates a unique slug for a given Doctrine_Record. This function enforces the uniqueness by
     * incrementing the values with a postfix if the slug is not unique
     *
     * @param Doctrine_Record $record
     * @param string $slugFromFields
     * @return string $slug
     */
    public function getUniqueSlug($record, $slugFromFields)
    {
        /* fix for use with Column Aggregation Inheritance */
        if ($record->getTable()->getOption('inheritanceMap')) {
          $parentTable = $record->getTable()->getOption('parents');
          $i = 0;
          // Be sure that you do not instanciate an abstract class;
          $reflectionClass = new ReflectionClass($parentTable[$i]);
          while ($reflectionClass->isAbstract()) {
            $i++;
            $reflectionClass = new ReflectionClass($parentTable[$i]);
          }
          $table = Doctrine_Core::getTable($parentTable[$i]);
        } else {
          $table = $record->getTable();
        }

        $name = $table->getFieldName($this->_options['name']);
        $proposal = call_user_func_array($this->_options['builder'], array($slugFromFields, $record));
		$proposal_base = $proposal;
		$proposal = call_user_func_array($this->_options['postProcess'], array($proposal, $record));
       

        $whereString = 'r.' . $name . ' LIKE ?';
        $whereParams = array('%'.$proposal_base.'%');

        if ($record->exists()) {
            $identifier = $record->identifier();
            $whereString .= ' AND r.' . implode(' != ? AND r.', $table->getIdentifierColumnNames()) . ' != ?';
            $whereParams = array_merge($whereParams, array_values($identifier));
        }

        foreach ($this->_options['uniqueBy'] as $uniqueBy) {
            if (is_null($record->$uniqueBy)) {
                $whereString .= ' AND r.'.$uniqueBy.' IS NULL';
            } else {
                $whereString .= ' AND r.'.$uniqueBy.' = ?';
                $value = $record->$uniqueBy;
                if ($value instanceof Doctrine_Record) {
                    $value = current((array) $value->identifier());
                }
                $whereParams[] =  $value;
            }
        }

        // Disable indexby to ensure we get all records
        $originalIndexBy = $table->getBoundQueryPart('indexBy');
        $table->bindQueryPart('indexBy', null);
		
        $query = $table->createQuery('r')
            ->select('r.' . $name)
            ->where($whereString , $whereParams)
            ->setHydrationMode(Doctrine_Core::HYDRATE_ARRAY);

        // We need to introspect SoftDelete to check if we are not disabling unique records too
        if ($table->hasTemplate('Doctrine_Template_SoftDelete')) {
	        $softDelete = $table->getTemplate('Doctrine_Template_SoftDelete');

	        // we have to consider both situations here
            if ($softDelete->getOption('type') == 'boolean') {
                $conn = $query->getConnection();

                $query->addWhere(
                    '(r.' . $softDelete->getOption('name') . ' = ' . $conn->convertBooleans(true) .
                    ' OR r.' . $softDelete->getOption('name') . ' = ' . $conn->convertBooleans(false) . ')'
                );
            } else {
                $query->addWhere('(r.' . $softDelete->getOption('name') . ' IS NOT NULL OR r.' . $softDelete->getOption('name') . ' IS NULL)');
            }
        }

        $similarSlugResult = $query->execute();
		
        $query->free();

        // Change indexby back
        $table->bindQueryPart('indexBy', $originalIndexBy);

        $similarSlugs = array();
        foreach ($similarSlugResult as $key => $value) {
            $similarSlugs[$key] = strtolower($value[$name]);
        }
		//pr($similarSlugResult);exit;
		//$slug = call_user_func_array($this->_options['postProcess'], array($proposal, $record));
		$test = $proposal;
		$slug = $proposal_base;
        $i = 1;
        while (in_array(strtolower($test), $similarSlugs)) {
			$base = $proposal_base.'-'.$i;
            $slug = call_user_func_array($this->_options['builder'], array($base, $record));
			$test = call_user_func_array($this->_options['postProcess'], array($slug, $record));
			
			//echo "[$test]";
            $i++;
			}
		
		//$slug = $test;
		//echo $slug; exit;
        // If slug is longer then the column length then we need to trim it
        // and try to generate a unique slug again
        $length = $table->getFieldLength($this->_options['name']);
        if (strlen($slug) > $length) {
            $slug = substr($slug, 0, $length - (strlen($i) + 1));
            $slug = $this->getUniqueSlug($record, $slug);
        }
		
        return  $slug;
    }
}