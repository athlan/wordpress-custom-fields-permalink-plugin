<?php

class PostWithMetaKey extends WP_UnitTestCase {

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

	function test_generates_permalink_to_post_using_meta_key() {
	    // given
        $this->permalinkSteps->givenPermalinkStructure("/%field_some_meta_key%/%postname%/");

        $postParams = [
	        'post_title' => 'Some post title',
            'meta_input' => [
                'some_meta_key' => 'Some meta value',
                'some_other_meta_key' => 'Some other meta value',
            ],
        ];
        $createdPostId = $this->factory()->post->create($postParams);

        // when & then
        $this->permalinkAsserter->hasPermalink($createdPostId, "/some-meta-value/some-post-title/");
	}

    function test_go_to_post_using_meta_key_permalink_structure() {
        // given
        $this->permalinkSteps->givenPermalinkStructure("/%field_some_meta_key%/%postname%/");

        $postParams = [
            'post_title' => 'Some post title',
            'meta_input' => [
                'some_meta_key' => 'Some meta value',
                'some_other_meta_key' => 'Some other meta value',
            ],
        ];
        $createdPostId = $this->factory()->post->create($postParams);

        // when
        $this->go_to('/some-meta-value/some-post-title/');

        // then
        $this->assertFalse(is_404());
        $this->assertEquals($createdPostId, get_the_ID());
    }
}
