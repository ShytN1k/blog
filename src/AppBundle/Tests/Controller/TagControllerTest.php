<?php

namespace AppBundle\Tests\Controller;

use AppBundle\Tests\TestBase;

class TagControllerTest extends TestBase
{
    public function testIndex()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/en/tag/1');

        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $this->assertContains('Tag:', $crawler->filter('.header span')->text());
        $this->assertContains('Home', $crawler->filter('.btn-default')->text());
        $this->assertContains('Tags:', $crawler->filter('.tag-cloud .title')->text());
        $this->assertContains('Top articles:', $crawler->filter('.top-articles .title')->text());
        $this->assertContains('Last comments:', $crawler->filter('.top-comments .title')->text());
    }
}
