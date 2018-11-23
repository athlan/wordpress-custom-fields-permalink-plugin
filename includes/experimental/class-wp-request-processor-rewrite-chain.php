<?php
/**
 * Class RulesRewriter
 *
 * @package WordPress_Custom_Fields_Permalink
 */

namespace CustomFieldsPermalink;

use CustomFieldsPermalink\WP_Request_Processor;
use WP_Query;

/**
 * Class WP_Request_Processor_Rewrite_Chain handles the process of the request.
 *
 * If some rewrite rule is matched, however is's raising 404 it i excluded
 * and core request processing begin again.
 */
class WP_Request_Processor_Rewrite_Chain {

	/**
	 * Cached rewrite rules.
	 *
	 * @var array|null
	 */
	private $cached_rewrite_rules = null;

	/**
	 * Filters the value of an existing option before it is retrieved.
	 *
	 * Intercepts getting value of rewrite_rules option and returns cached ones.
	 *
	 * @return mixed
	 *
	 * @link https://developer.wordpress.org/reference/hooks/pre_option_option/
	 */
	public function pre_option_rewrite_rules() {
		if ( null === $this->cached_rewrite_rules ) {
			return false;
		}

		return $this->cached_rewrite_rules;
	}

	/**
	 * Filters the value of an existing option.
	 *
	 * Caches first retreive of rewrite_rules option.
	 *
	 * @param mixed $value Value of the option.
	 *
	 * @link https://developer.wordpress.org/reference/hooks/option_option/
	 */
	public function option_rewrite_rules( $value ) {
		$this->cached_rewrite_rules = $value;
	}

	/**
	 * Filters whether to short-circuit default header status handling.
	 *
	 * If page gives 404 and custom field rule has been involved, then exclude it and
	 * start requst processing again without that rule.
	 *
	 * @param bool     $preempt Whether to short-circuit default header status handling. Default false.
	 * @param WP_Query $wp_query WordPress Query object.
	 *
	 * @return bool Returning a non-false value from the filter will short-circuit the handling
	 * and return early.
	 *
	 * @link https://developer.wordpress.org/reference/hooks/pre_handle_404/
	 */
	public function pre_handle_404( $preempt, $wp_query ) {
		global $wp;

		if ( $wp_query->query_vars[ WP_Request_Processor::PARAM_CUSTOMFIELD_PARAMS ] ) {
			unset( $this->cached_rewrite_rules[ $wp->matched_rule ] );
			$this->do_wp_again();
			return false;
		}

		return true;
	}

	/**
	 * Runs wp() lifecycle again.
	 */
	public function do_wp_again() {
		global $wp;

		$query_args = $wp->extra_query_vars;
		$wp->parse_request( $query_args );
		$wp->send_headers();
		$wp->query_posts();
		$wp->handle_404();
	}
}
