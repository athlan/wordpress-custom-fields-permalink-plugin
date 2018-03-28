<?php

class PostWithMissingMetaKey extends WP_UnitTestCase {

    /**
     * @var PermalinkSteps
     */
    private $permalinkSteps;

    /**
     * @var PermalinkAsserter
     */
    private $permalinkAsserter;

    public function setUp() {
        parent::setUp();

        $this->permalinkSteps = new PermalinkSteps($this);
        $this->permalinkAsserter = new PermalinkAsserter($this);
    }

	function test_generates_permalink_to_post_while_missing_meta_key() {
	    // given
        $this->permalinkSteps->given_permalink_structure("/%field_some_meta_key%/%postname%/");

        $postParams = [
	        'post_title' => 'Some post title',
            'meta_input' => [
                // There is missing meta key here
                //'some_meta_key' => 'Some meta value',
            ],
        ];
        $createdPostId = $this->factory()->post->create($postParams);

        // when & then
        $this->permalinkAsserter->has_permalink($createdPostId, "/some-post-title/");
	}

    function test_go_to_post_when_missing_meta_key() {
        // given
        $this->permalinkSteps->given_permalink_structure("/%field_some_meta_key%/%postname%/");

        $postParams = [
            'post_title' => 'Some post title',
            'meta_input' => [
                // There is missing meta key here
                //'some_meta_key' => 'Some meta value',
            ],
        ];
        $createdPostId = $this->factory()->post->create($postParams);

        // when
        $this->go_to('/inexisting-meta-value/some-post-title/');

        // then
        $this->assertTrue(is_404());
    }
}
