<?php
/*
Plugin Name: Add a Single Footer Page Widget
Description: A simple widget that lets you add a single page to your footer. (Usually they are a bunch of links so this is an individual thing)
Version: 1.0
Author: Kat Padilla
Author URI: http://katpadi.ph
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Copyright 2014 (awesome@katpadi.ph)

This program is free software; you can redistribute it and/or modify
it under the terms of the GNU General Public License, version 2, as
published by the Free Software Foundation.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
*/

class SI_Widget_Footer_Pages extends WP_Widget {

  /**
   * Register widget with WordPress.
   */
  public function __construct() {
    parent::__construct(
      'footer_pages', // Base ID
      'A Footer Page', // Name

      array( 'description' => __( 'A single footer page', 'text_domain' ), ) // Args
    );
  }

  /**
   * Front-end display of widget.
   *
   * @see WP_Widget::widget()
   */
  public function widget( $args, $instance ) {
    extract( $args );
    $possible_footer_page = apply_filters( 'possible_footer_page', $instance['possible_footer_page'] );
        $args = array(
            'p' => $possible_footer_page,
            'post_type' => 'page');
        $footer_pages = new WP_Query( $args);

        if ($footer_pages->have_posts()) : while ($footer_pages->have_posts()) : $footer_pages->the_post();?>
        <li><a href="<?php the_permalink(); ?>"><?php the_title();?></a></li>
        <?php endwhile; endif; ?>
    <?php

  }

  /**
   * Sanitize widget form values as they are saved.
   *
   * @see WP_Widget::update()
   */
  public function update( $new_instance, $old_instance ) {
    $instance = $old_instance;
    $instance['possible_footer_page'] = strip_tags( $new_instance['possible_footer_page'] );

    return $instance;
  }

  /**
   * Back-end widget form.
   *
   * @see WP_Widget::form()
   *
   * @param array $instance Previously saved values from database.
   */
  public function form( $instance ) {
        $possible_footer_page = $instance['possible_footer_page'];
        $pagez_args = array(
          'post_type' => 'page',
          'posts_per_page' => '-1'
        );
        $pagez = new WP_Query( $pagez_args );
    ?>
    <p>
        <fieldset>
        <label for="<?php echo $this->get_field_id( 'possible_footer_page' ); ?>">Select a page to display:</label>
        <select id="<?php echo $this->get_field_id( 'possible_footer_page' ); ?>" name="<?php echo $this->get_field_name('possible_footer_page');?> ">
            <?php if ($pagez->have_posts()) : while ($pagez->have_posts()) : $pagez->the_post();?>
            <option value="<?php the_ID(); ?>" <?php selected( $possible_footer_page, get_the_ID()); ?>><?php the_title();?></option>
            <?php endwhile; endif; ?>
        </select>
        </fieldset>
    </p>
    <?php
  }

}

add_action( 'widgets_init', create_function( '', 'register_widget( "SI_Widget_Footer_Pages" );' ) );
