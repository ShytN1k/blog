<?php

namespace AppBundle\Tests\Controller;

use AppBundle\Tests\TestBase;

class DefaultControllerTest extends TestBase
{
    public function testIndex()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/en/');

        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $this->assertContains('Tags:', $crawler->filter('.tag-cloud .title')->text());
        $this->assertContains('Top articles:', $crawler->filter('.top-articles .title')->text());
        $this->assertContains('Last comments:', $crawler->filter('.top-comments .title')->text());
        $this->assertCount(5, $crawler->filter('.article'));
    }
}
