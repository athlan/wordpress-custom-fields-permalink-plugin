<?php
/**
 * Tests util file.
 *
 * @package WordPress_Custom_Fields_Permalink
 */

/**
 * Class CustomPostTypeSteps contains utility methods to setup custom post types.
 */
class CustomPostTypeSteps {

	/**
	 * CustomPostTypeSteps constructor.
	 */
	public function __construct() {
	}

	/**
	 * Sets the given custom post type.
	 *
	 * @param string $post_type Post type.
	 *
	 * @return CustomPostTypeStepsBuilder
	 */
	public function given_custom_post_type( $post_type ) {
		return new CustomPostTypeStepsBuilder( $post_type );
	}
}

/**
 * Class CustomPostTypeStepsBuiler contains utility methods to setup custom post types.
 */
class CustomPostTypeStepsBuilder {

	/**
	 * Custom post type name.
	 *
	 * @var string
	 */
	private $post_type = null;

	/**
	 * Custom post type rewrite slug.
	 *
	 * @var string
	 */
	private $rewrite_slug = null;

	/**
	 * PermalinkSteps constructor.
	 *
	 * @param string $post_type Post type.
	 */
	public function __construct( $post_type ) {
		$this->post_type = $post_type;
	}

	/**
	 * Sets the given permalink structure.
	 */
	public function end() {
		$this->init();
	}

	/**
	 * Initializes all.
	 */
	private function init() {
		$args = array(
			'label'    => $this->post_type,
			'supports' => array( 'title', 'editor', 'custom-fields' ),
			'public'   => true,
		);

		if ( $this->rewrite_slug ) {
			$args['rewrite'] = array(
				'slug' => $this->rewrite_slug,
			);
		}

		register_post_type( $this->post_type, $args );

		flush_rewrite_rules();
	}

	/**
	 * Sets the given rewrite slug.
	 *
	 * @param string $rewrite_slug Rewrite slug.
	 *
	 * @return CustomPostTypeStepsBuilder
	 */
	public function with_rewrite_slug( $rewrite_slug ) {
		$this->rewrite_slug = $rewrite_slug;
		return $this;
	}
}
