<?php

/**
 * @wp.posttype.name                        custom_post_type
 * @wp.posttype.single                      Custom
 * @wp.posttype.plural                      Customs
 *
 * @wp.posttype.args.public                 true
 * @wp.posttype.args.publicly_queryable     true
 * @wp.posttype.args.show_ui                true
 * @wp.posttype.args.show_in_menu           true
 * @wp.posttype.args.query_var              true
 * @wp.posttype.args.rewrite                true
 * @wp.posttype.args.capability_type        post
 * @wp.posttype.args.has_archive            true
 * @wp.posttype.args.hierarchical           false
 * @wp.posttype.args.menu_position          null
 * 
 * @wp.posttype.supports.title              true
 * @wp.posttype.supports.editor             true
 * @wp.posttype.supports.thumbnail          false
 * @wp.posttype.supports.excerpt            false
 * @wp.posttype.supports.comments           false
 *
 * @wp.posttype.labels.name                 %Plural%
 * @wp.posttype.labels.singular_name        %Single%
 * @wp.posttype.labels.add_new              New %Single%
 * @wp.posttype.labels.add_new_item         Add New %Single%
 * @wp.posttype.labels.edit_item            Edit %Single%
 * @wp.posttype.labels.new_item             New %Single%
 * @wp.posttype.labels.all_items            All %Plural%
 * @wp.posttype.labels.view_item            View %Single%
 * @wp.posttype.labels.search_items         Search %Plural%
 * @wp.posttype.labels.not_found            No %plural% found
 * @wp.posttype.labels.not_found_in_trash   No %plural% found in Trash
 * @wp.posttype.labels.menu_name            %Plural%
 * 
 * -@wp.posttype.label.parent_item_colon
 * 
 */
class Snap_Wordpress_PostType extends Snap_Wordpress_Plugin
{
    protected $name;
    protected $single;
    protected $plural;
    
    /**
     * @wp.action
     */
    public function init()
    {        
        $this->snap = Snap::get( $this );
        
        $args = $this->snap->klass('wp.posttype.args');
        $labels = $this->snap->klass('wp.posttype.labels');
        
        $this->name = $this->snap->klass('wp.posttype.name', $this->name );
        $this->single = $this->snap->klass('wp.posttype.single', $this->single );
        $this->plural = $this->snap->klass('wp.posttype.plural', $this->plural );
        
        $args['labels'] = $this->parseLabels($labels);
        $args['supports'] = array();
        
        $supports = $this->snap->klass('wp.posttype.supports');
        foreach( $supports as $name => $support ){
            if( $support ) $args['supports'][] = $name;
        }
        
        $args = apply_filters('snap_posttype_args', $args);
        register_post_type( $this->name, $this->filterArgs($args) );
    }
    
    protected function filterArgs( $args )
    {
        return $args;
    }
    
    protected function parseLabels( $labels )
    {
        $replacements = array(
            '%single%'      => strtolower($this->single),
            '%plural%'      => strtolower($this->plural),
            '%Single%'      => $this->single,
            '%Plural%'      => $this->plural
        );
        $from = array_keys( $replacements );
        $to = array_values( $replacements );
        foreach( $labels as $key => $value ){
            $labels[$key] = str_replace( $from, $to, $value );
        }
        return $labels;
    }
    
    public function _wp_add_meta_box( $id, $title, $callback, $post_type, $context, $priority ){
        if( !$post_type ) $post_type = $this->name;
        add_meta_box( $id, $title, $callback, $post_type, $context, $priority );
    }
    
}