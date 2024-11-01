<?php

/*
  Plugin Name: WP Link Pages Extended
  Plugin URI: http://www.terryobrien.com/programming/wordpress/plugins/
  Description: Extended the Post and Page 'nextpage' pagination navigation system with something more informative
  Version: 1.0
  Author: Terry O'Brien (HoosierDragon)
  Author URI: http://www.terryobrien.com/
  License: GPLv2
 */

/*
  Copyright (C) 2014-2015 Terry O'Brien

  This program is free software; you can redistribute it and/or
  modify it under the terms of the GNU General Public License
  as published by the Free Software Foundation; either version 2
  of the License, or (at your option) any later version.

  This program is distributed in the hope that it will be useful,
  but WITHOUT ANY WARRANTY; without even the implied warranty of
  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
  GNU General Public License for more details.

  You should have received a copy of the GNU General Public License
  along with this program; if not, write to the Free Software
  Foundation, Inc., 59 Temple Place - Suite 330, Boston, MA  02111-1307, USA.
 */

class wpLinkPagesExtended
{
    public function __construct()
    {
        add_filter( 'wp_link_pages_args', array( $this, 'wp_link_pages_extended_args' ) );

        add_action( 'admin_init', array( $this, 'wp_link_pages_extended_admin_init' ) );
        add_filter( 'query_vars', array( $this, 'wp_link_pages_extended_parameter_queryvars' ) );
        add_action( 'the_post', array( $this, 'wp_link_pages_extended_the_post' ), 0 );

        load_plugin_textdomain( 'wp-link-pages-extended', false, dirname( plugin_basename( __FILE__ ) ) );
    }

    function wp_link_pages_extended_parameter_queryvars( $queryvars )
    {
        $queryvars[] = 'viewall';
        return( $queryvars );
    }

    function wp_link_pages_extended_args( $default )
    {
        global $page, $post, $numpages, $wp_query;

        $args = array(
            'next_or_number' => 'number', // keep numbering for the main part
            'pagelink' => '%',
            'separator' => ' | ',
            'echo' => 1,
            'before' => '',
            'after' => '',
            'link_before' => '',
            'link_after' => '',
            'nextpagelink' => __( 'Next', 'wp-link-pages-extended' ),
            'previouspagelink' => __( 'Prev', 'wp-link-pages-extended' ),
            'firstpagelink' => __( 'First', 'wp-link-pages-extended' ), //  Not standard
            'lastpagelink' => __( 'Last', 'wp-link-pages-extended' ), //  Not standard
            'viewalllink' => __( 'View All', 'wp-link-pages-extended' ), //  Not standard
            'viewpageslink' => __( 'View Pages', 'wp-link-pages-extended' ), //  Not standard
        );

        /*
         *  If the entry is on the front page or the blog front page
         *  Then do not show the links
         */
        if ( is_home() )
        {
            return( array( 'echo' => 0 ) );
        }
        /*
         *  If the page or post is the whole page or post because of the "viewall" parameter
         *  Then show the links to the sub-pages so the view can get out of the whole page view
         */
        else
        if ( isset( $wp_query -> query_vars[ 'viewall' ] ) && ( 'true' === $wp_query -> query_vars[ 'viewall' ] ) )
        {
            /*
             *  Count the true number of pages here, somewhere it is being reset in certain situations
             */
            $numpages = substr_count( $post -> post_content, 'class="wp-link-pages-extended"' ) + 1;

            /*
             *  Before page: open section
             */
            $args[ 'before' ] .= '<div style="clear:both; display:block; text-align: center;" >';
            $args[ 'before' ] .= '<p class="post-pagination">';

            /*
             *  Before page: first page link
             *  (Using get_pagenum_link() here also returns the "viewall=true" parameter which defeats the purpose of the link
             */
            $args[ 'before' ] .= _wp_link_page( 1 ) . $args[ 'firstpagelink' ] . '</a>';
            $args[ 'before' ] .= $args[ 'separator' ];

            /*
             *  After page: final links
             */
            $args[ 'after' ] .= $args[ 'separator' ];
            $args[ 'after' ] .= _wp_link_page( $numpages ) . $args[ 'lastpagelink' ] . '</a>';

            /*
             *  View All link handing
             */
            $args[ 'after' ] .= '<br/>' . $args[ 'viewpageslink' ];

            $args[ 'after' ] .= '</p>';
            $args[ 'after' ] .= '</div>';

            return( $args );
        }
        /*
         *  If there are multiple pages
         *  Then create the array entires to display all desired links
         */
        else
        if ( 1 < $numpages )
        {
            /*
             *  Before page: open section, display page counter above list, then first and previous links
             */
            $args[ 'before' ] .= '<div style="clear:both; display:block; text-align: center;" >';
            $args[ 'before' ] .= '<p class="post-pagination">';
            $args[ 'before' ] .= sprintf( '%s %d %s %d', __( 'Page', 'wp-link-pages-extended' ), $page, __( 'of', 'wp-link-pages-extended' ), $numpages );
            $args[ 'before' ] .= '<br />';

            if ( 1 === $page )
            {
                $args[ 'before' ] .= $args[ 'firstpagelink' ];
                $args[ 'before' ] .= $args[ 'separator' ];
                $args[ 'before' ] .= $args[ 'previouspagelink' ];
                $args[ 'before' ] .= $args[ 'separator' ];
            }
            else
            {
                $args[ 'before' ] .= _wp_link_page( 1 ) . $args[ 'firstpagelink' ] . '</a>';
                $args[ 'before' ] .= $args[ 'separator' ];
                $args[ 'before' ] .= _wp_link_page( $page - 1 ) . $args[ 'previouspagelink' ] . '</a>';
                $args[ 'before' ] .= $args[ 'separator' ];
            }

            /*
             *  After page: next and final links
             */
            if ( $page < $numpages )
            {
                $args[ 'after' ] .= $args[ 'separator' ];
                $args[ 'after' ] .= _wp_link_page( $page + 1 ) . $args[ 'nextpagelink' ] . '</a>';
                $args[ 'after' ] .= $args[ 'separator' ];
                $args[ 'after' ] .= _wp_link_page( $numpages ) . $args[ 'lastpagelink' ] . '</a>';
            }
            else
            {
                $args[ 'after' ] .= $args[ 'separator' ];
                $args[ 'after' ] .= $args[ 'nextpagelink' ];
                $args[ 'after' ] .= $args[ 'separator' ];
                $args[ 'after' ] .= $args[ 'lastpagelink' ];
            }

            /*
             *  View All link handing
             *  Add return link to view all pages
             */
            $args[ 'after' ] .= '<br/>';
            $args[ 'after' ] .= '<a href="' . get_pagenum_link( 1 ) . ( preg_match( '/\?/', get_pagenum_link( 1 ) ) ? '&' : '?' ) . 'viewall=true' . '">' . $args[ 'viewalllink' ] . '</a>';

            $args[ 'after' ] .= '</p>';
            $args[ 'after' ] .= '</div>';

            /*
             *  Overwrite the default parameters with the assigned params above and return them
             */
            return ( $args );
        }
        /*
         *  If there are no separate pages, only one comprehensive page, then return the default array since it won't be used anyway
         */
        else
        {
            return( $default );
        }
    }

    /*
     *  Add functionality to put the links in the plugin listing entry
     */
    function wp_link_pages_extended_admin_init()
    {
        global $pagenow;

        /*
         *  Add details to the plugin description
         *  Details only appear when plugin is activated
         */
        if ( in_array( $pagenow, array( 'plugins.php', ) ) )
        {
            add_filter( 'plugin_row_meta', array( $this, 'wp_link_pages_extended_admin_init_meta_links' ), 10, 2 );
        }
    }

    /*
     *  Add links to plugin description section
     */
    function wp_link_pages_extended_admin_init_meta_links( $links, $file )
    {
        /*
         *  Use basename for parent sub-directory name and file name, must be same
         */
        if ( basename( __FILE__ ) == basename( $file ) )
        {
            /*
             *  Using array_push puts the Download and Donate link at the end of the sequence
             */
            array_push( $links, '<a href="http://wordpress.org/extend/plugins/wp-link-pages-extended/">Download</a>' );
            array_push( $links, '<a href="https://www.paypal.com/cgi-bin/webscr?cmd=s-xclick&hosted_button_id=THLBLFT4BV7E2">Donate</a>' );
        }

        return $links;
    }

    /*
     *  Add functionality to rewrite post / page to remove <!--nextpage--> so the whole page will be displayed
     */
    function wp_link_pages_extended_the_post( $post )
    {
        global $pages, $multipage, $wp_query;

        if ( isset( $wp_query -> query_vars[ 'viewall' ] ) && ( 'true' === $wp_query -> query_vars[ 'viewall' ] ) )
        {
            $multipage = true;
            $post -> post_content = str_replace( '<!--nextpage-->', '<hr class="wp-link-pages-extended" style="clear:both;" />', $post -> post_content );
            $pages = array( $post -> post_content );
        }
    }

}

$wpLinkPagesExtended = new wpLinkPagesExtended;
?>