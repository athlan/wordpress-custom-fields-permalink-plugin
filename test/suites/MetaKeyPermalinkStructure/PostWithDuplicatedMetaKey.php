<?php

class PostWithDuplicatedMetaKey extends WP_UnitTestCase {

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

	function test_generates_permalink_to_post_while_duplicated_meta_key() {
	    // given
        $this->permalinkSteps->givenPermalinkStructure("/%field_some_meta_key%/%postname%/");

        $someMetaKey = 'some_meta_key';
        $postParams = [
	        'post_title' => 'Some post title',
            'meta_input' => [
                $someMetaKey => 'Some meta value',
            ],
        ];
        $createdPostId = $this->factory()->post->create($postParams);
        add_post_meta($createdPostId, $someMetaKey, 'Some duplicated meta value');

        // when
        $createdPostMetaValues = get_post_meta($createdPostId);

        // then
        $this->assertCount(2, $createdPostMetaValues[$someMetaKey]);
        $this->permalinkAsserter->hasPermalink($createdPostId, "/some-meta-value/some-post-title/");
	}

    function test_go_to_post_when_duplicated_meta_key_and_use_first_one() {
        // given
        $this->permalinkSteps->givenPermalinkStructure("/%field_some_meta_key%/%postname%/");

        $someMetaKey = 'some_meta_key';
        $postParams = [
            'post_title' => 'Some post title',
            'meta_input' => [
                $someMetaKey => 'Some meta value',
            ],
        ];
        $createdPostId = $this->factory()->post->create($postParams);
        add_post_meta($createdPostId, $someMetaKey, "Some duplicated meta value");

        // when
        $this->go_to('/some-meta-value/some-post-title/');

        // then
        $this->assertFalse(is_404());
        $this->assertEquals($createdPostId, get_the_ID());
    }

    function test_go_to_post_when_duplicated_meta_key_and_use_duplicate_one() {
        // given
        $this->permalinkSteps->givenPermalinkStructure("/%field_some_meta_key%/%postname%/");

        $someMetaKey = 'some_meta_key';
        $postParams = [
            'post_title' => 'Some post title',
            'meta_input' => [
                $someMetaKey => 'Some meta value',
            ],
        ];
        $createdPostId = $this->factory()->post->create($postParams);
        add_post_meta($createdPostId, $someMetaKey, "Some duplicated meta value");

        // when
        $this->go_to('/some-duplicated-meta-value/some-post-title/');

        // then
        $this->assertFalse(is_404());
        $this->assertEquals($createdPostId, get_the_ID());
    }
}
