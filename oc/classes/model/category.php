<?php defined('SYSPATH') or die('No direct script access.');
/**
 * description...
 *
 * @author		Chema <chema@open-classifieds.com>
 * @package		OC
 * @copyright	(c) 2009-2013 Open Classifieds Team
 * @license		GPL v3
 * *
 */
class Model_Category extends ORM {


	/**
	 * Table name to use
	 *
	 * @access	protected
	 * @var		string	$_table_name default [singular model name]
	 */
	protected $_table_name = 'categories';

	/**
	 * Column to use as primary key
	 *
	 * @access	protected
	 * @var		string	$_primary_key default [id]
	 */
	protected $_primary_key = 'id_category';


	/**
	 * @var  array  ORM Dependency/hirerachy
	 */
	protected $_has_many = array(
		'ads' => array(
			'model'       => 'Ad',
			'foreign_key' => 'id_category',
		),
	);

    protected $_belongs_to = array(
        'parent'   => array('model'       => 'Category',
                            'foreign_key' => 'id_category_parent'),
    );

    /**
     * global Model Category instance get from controller so we can access from anywhere like Model_Category::current()
     * @var Model_Location
     */
    protected static $_current = NULL;

    /**
     * returns the current category
     * @return Model_Category
     */
    public static function current()
    {
        //we don't have so let's retrieve
        if (self::$_current === NULL)
        {
            self::$_current = new self();
            if(Request::current()->param('category') != URL::title(__('all')))
            {
                self::$_current = self::$_current->where('seoname', '=', Request::current()->param('category'))
                                                    ->limit(1)->cached()->find();
            }
        }

        return self::$_current;
    }


	/**
	 * Rule definitions for validation
	 *
	 * @return array
	 */
	public function rules()
	{
		return array('id_category'		=> array(array('numeric')),
			        'name'				=> array(array('not_empty'), array('max_length', array(':value', 145)), ),
			        'order'				=> array(),
			        'id_category_parent'=> array(),
			        'parent_deep'		=> array(),
			        'seoname'			=> array(array('not_empty'), array('max_length', array(':value', 145)), ),
			        'description'		=> array(),
			        'price'				=> array(), );
	}

	/**
	 * Label definitions for validation
	 *
	 * @return array
	 */
	public function labels()
	{
		return array('id_category'			=> __('Id'),
			        'name'					=> __('Name'),
			        'order'					=> __('Order'),
			        'created'				=> __('Created'),
			        'id_category_parent'	=> __('Parent'),
			        'parent_deep'			=> __('Parent deep'),
			        'seoname'				=> __('Seoname'),
			        'description'			=> __('Description'),
			        'price'					=> __('Price'));
	}
	
    /**
     * Filters to run when data is set in this model. The password filter
     * automatically hashes the password when it's set in the model.
     *
     * @return array Filters
     */
    public function filters()
    {
        return array(
                'seoname' => array(
                                array(array($this, 'gen_seoname'))
                              ),
                'id_category_parent' => array(
                                array(array($this, 'check_parent'))
                              ),
        );
    }

    /**
     * we get the categories in an array 
     * @return array 
     */
    public static function get_as_array()
    {
        //transform the cats to an array
        if ( ($cats_arr = Core::cache('cats_arr'))===NULL)
        {
            $cats = new self;
            $cats = $cats->order_by('order','asc')->find_all()->cached()->as_array('id_category');

            $cats_arr = array();
            foreach ($cats as $cat) 
            {
                $cats_arr[$cat->id_category] =  array('name'               => $cat->name,
                                                      'order'              => $cat->order,
                                                      'id_category_parent' => $cat->id_category_parent,
                                                      'parent_deep'        => $cat->parent_deep,
                                                      'seoname'            => $cat->seoname,
                                                      'price'              => $cat->price,
                                                      'id'                 => $cat->id_category,
                                                    );
            }
            Core::cache('cats_arr',$cats_arr);
        }   

        return $cats_arr;
    }

    /**
     * we get the categories in an array using as key the deep they are, perfect fro chained selects
     * @return array 
     */
    public static function get_by_deep()
    {
        // array by parent deep, 
        // each parent deep is one array with categories of the same index
        if ( ($cats_parent_deep = Core::cache('cats_parent_deep'))===NULL)
        {
            $cats = new self;
            $cats = $cats->order_by('order','asc')->find_all()->cached()->as_array('id_category');

            $cats_parent_deep = array();
            foreach ($cats as $cat) 
            {
                $cats_parent_deep[$cat->parent_deep][$cat->id_category] =  array('name'               => $cat->name,
                                                                                  'id_category_parent' => $cat->id_category_parent,
                                                                                  'parent_deep'        => $cat->parent_deep,
                                                                                  'seoname'            => $cat->seoname,
                                                                                  'price'              => $cat->price,
                                                                                  'id'                 => $cat->id_category,
                                                                        );
            }
            //sort by key, in case lover level is befor higher
            ksort($cats_parent_deep);
            Core::cache('cats_parent_deep',$cats_parent_deep);
        }

        return $cats_parent_deep;
    }


    /**
     * we get the categories in an array miltidimensional by deep.
     * @return array 
     */
    public static function get_multidimensional()
    {
        //multidimensional array
        if ( ($cats_m = Core::cache('cats_m'))===NULL)
        {
            $cats = new self;
            $cats = $cats->order_by('order','asc')->find_all()->cached()->as_array('id_category');

            //for each category we get his siblings
            $cats_s = array();
            foreach ($cats as $cat) 
                 $cats_s[$cat->id_category_parent][] = $cat->id_category;
            

            //last build multidimensional array
            if (count($cats_s)>1)
                $cats_m = self::multi_cats($cats_s);
            else
                $cats_m = array();
            Core::cache('cats_m',$cats_m);
        }

        return $cats_m;
    }

    /**
     * gets a multidimensional array wit the categories
     * @param  array  $cats_s      id_category->array(id_siblings)
     * @param  integer $id_category 
     * @param  integer $deep        
     * @return array               
     */
    public static function multi_cats($cats_s,$id_category = 1, $deep = 0)
    {    
        $ret = NULL;
        //we take all the siblings and try to set the grandsons...
        //we check that the id_category sibling has other siblings
        if (isset($cats_s[$id_category]))
        {
            foreach ($cats_s[$id_category] as $id_sibling) 
            {
                //we check that the id_category sibling has other siblings
                if (isset($cats_s[$id_sibling]))
                {
                    if (is_array($cats_s[$id_sibling]))
                    {
                        $ret[$id_sibling] = self::multi_cats($cats_s,$id_sibling,$deep+1);
                    }
                }
                //no siblings we only set the key
                else 
                    $ret[$id_sibling] = NULL;
                
            }
        }
        return $ret;
    }

    /**
     * we get the categories in an array and a multidimensional array to know the deep @todo refactor this, is a mess
     * @deprecated function not in use, just here so we do not break the API to old themes
     * @return array 
     */
    public static function get_all()
    {
        //as array
        $cats_arr = self::get_as_array();

        //multidimensional array
        $cats_m = self::get_multidimensional();

        //array by deep
        $cats_parent_deep = self::get_by_deep();
        
        return array($cats_arr,$cats_m, $cats_parent_deep);
    }


	/**
	 * counts how many ads have each category
	 * @return array
	 */
	public static function get_category_count()
	{
        if ( ($cats_count = Core::cache('cats_count'))===NULL)
        {

            $expr_date = core::config('advertisement.expire_date');
            $db_prefix = core::config('database.default.table_prefix');

            //getting categories and how many ads have a category.
            $cats = DB::select('c.*')
                    ->select(array(DB::select(DB::expr('COUNT("id_ad")'))
                            ->from(array('ads','a'))
                            ->where('a.id_category','=',DB::expr($db_prefix.'c.id_category'))
                            ->where(DB::expr('IF('.$expr_date.' <> 0, DATE_ADD( published, INTERVAL '.$expr_date.' DAY), DATE_ADD( NOW(), INTERVAL 1 DAY))'), '>', Date::unix2mysql())
                            ->where('a.status','=',Model_Ad::STATUS_PUBLISHED)
                            ->group_by('id_category'), 'count'))
                    ->from(array('categories', 'c'))
                    ->order_by('order','asc')
                    ->as_object()
                    ->cached()
                    ->execute();

            //array where we store the categories with the count
            $cats_count = array();

            //array to store parents_id with the count. So later we can easily add them up
            $parent_count = array();

            foreach ($cats as $c) 
            {

                $cats_count[$c->id_category] = array(   'id_category'   => $c->id_category,
                                                        'seoname'       => $c->seoname,
                                                        'name'          => $c->name,
                                                        'id_category_parent'        => $c->id_category_parent,
                                                        'parent_deep'   => $c->parent_deep,
                                                        'order'         => $c->order,
                                                        'price'         => $c->price,
                                                        'has_siblings'  => FALSE,
                                                        'count'         => (is_numeric($c->count))?$c->count:0
                                                        );
                //counting the ads the parent have
                if ($c->id_category_parent!=0)
                {
                    if (!isset($parent_count[$c->id_category_parent]))
                        $parent_count[$c->id_category_parent] = 0;

                    $parent_count[$c->id_category_parent]+=$c->count;
                }
                
            }

            foreach ($parent_count as $id_category => $count) 
            {
                //attaching the count to the parents so we know each parent how many ads have
                $cats_count[$id_category]['count'] += $count;
                $cats_count[$id_category]['has_siblings'] = TRUE;
            }

            Core::cache('cats_count',$cats_count);
        }  
		
		return $cats_count;
	}

    /**
     * returns all the siblings ids+ the idcategory, used to filter the ads
     * @return array
     */
	public function get_siblings_ids()
    {
        if ($this->loaded())
        {
            //name used in the cache for storage
            $cache_name = 'get_siblings_ids_category_'.$this->id_category;

            if ( ($ids_siblings = Core::cache($cache_name))===NULL)
            {
                //array that contains all the siblings as keys (1,2,3,4,..)
                $ids_siblings = array();

                //we add himself as we use the clause IN on the where
                $ids_siblings[] = $this->id_category;

                $categories = new self();
                $categories = $categories->where('id_category_parent','=',$this->id_category)->cached()->find_all();

                foreach ($categories as $category) 
                {
                    $ids_siblings[] = $category->id_category;

                    //adding his children recursevely if they have any
                    if ( count($siblings_cats = $category->get_siblings_ids())>1 ) 
                        $ids_siblings = array_merge($ids_siblings,$siblings_cats);       
                }

                //removing repeated values
                $ids_siblings = array_unique($ids_siblings);

                //cache the result is expensive!
                Core::cache($cache_name,$ids_siblings);
            }

            return $ids_siblings;
        }

        //not loaded
        return NULL;
    }

	/**
	 * 
	 * formmanager definitions
	 * 
	 */
	public function form_setup($form)
	{	
		$form->fields['description']['display_as'] = 'textarea';

        $form->fields['id_category_parent']['display_as']   = 'select';
        $form->fields['id_category_parent']['caption']      = 'name';   

        $form->fields['order']['display_as']   = 'select';
        $form->fields['order']['options']      = range(1, 100);
	}


	public function exclude_fields()
	{
		return array('created','parent_deep');
	}

    /**
     * return the title formatted for the URL
     *
     * @param  string $title
     * 
     */
    public function gen_seoname($seoname)
    {
        //in case seoname is really small or null
        if (strlen($seoname)<3)
            $seoname = $this->name;

        $seoname = URL::title($seoname);

        //this are reserved categories names used in the routes.php
        $banned_names = array('blog','faq','forum','oc-panel','rss','oc-error',URL::title(__('all')),'user','stripe','paymill','bitpay','paypal');
        //same name as a route..shit!
        if (in_array($seoname, $banned_names))
            $seoname = URL::title(__('category')).'-'.$seoname; 

        if ($seoname != $this->seoname)
        {
            $cat = new self;
            //find a user same seoname
            $s = $cat->where('seoname', '=', $seoname)->limit(1)->find();

            //found, increment the last digit of the seoname
            if ($s->loaded())
            {
                $cont = 2;
                $loop = TRUE;
                while($loop)
                {
                    $attempt = $seoname.'-'.$cont;
                    $cat = new self;
                    unset($s);
                    $s = $cat->where('seoname', '=', $attempt)->limit(1)->find();
                    if(!$s->loaded())
                    {
                        $loop = FALSE;
                        $seoname = $attempt;
                    }
                    else
                    {
                        $cont++;
                    }
                }
            }
        }
        

        return $seoname;
    }

    /**
     * rule to verify that we selected a parent if not put the root location
     * @param  integer $id_parent 
     * @return integer                     
     */
    public function check_parent($id_parent)
    {
        return (is_numeric($id_parent))? $id_parent:1;
    }

    /**
     * returns the deep of parents of this category
     * @return integer
     */
    public function get_deep()
    {
        //initial deep
        $deep = 0;

        if ($this->loaded())
        {
            //getting all the cats as array
            $cats_arr = Model_Category::get_as_array();

            //getin the parent of this category
            $id_category_parent = $cats_arr[$this->id_category]['id_category_parent'];

            //counting till we find the begining
            while ($id_category_parent != 1 AND $id_category_parent != 0 AND $deep<100) 
            {
                $id_category_parent = $cats_arr[$id_category_parent]['id_category_parent'];
                $deep++;
            }
        }
        
        return $deep;
    }

} // END Model_Category