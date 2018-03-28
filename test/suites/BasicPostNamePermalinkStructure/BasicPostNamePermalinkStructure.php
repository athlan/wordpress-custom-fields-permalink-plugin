<?php

class BasicPostNamePermalinkStructure extends WP_UnitTestCase {

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

	function test_generates_permalink_to_post() {
	    // given
        $this->permalinkSteps->given_postname_permalink_structure();

        $postParams = [
	        'post_title' => 'Some post title',
            'meta_input' => [
                'some_meta_key' => 'Some meta value',
            ],
        ];
        $createdPostId = $this->factory()->post->create($postParams);

        // when & then
        $this->permalinkAsserter->has_permalink($createdPostId, "/some-post-title/");
	}

    function test_go_to_post_when_simple_postname_permalink_structure_and_plugin_activated() {
        // given
        $this->permalinkSteps->given_postname_permalink_structure();

        $postParams = [
            'post_title' => 'Some post title',
            'meta_input' => [
                'some_meta_key' => 'Some meta value',
            ],
        ];
        $createdPostId = $this->factory()->post->create($postParams);

        // when
        $this->go_to('/some-post-title/');

        // then
        $this->assertFalse(is_404());
        $this->assertEquals($createdPostId, get_the_ID());
    }
}
