<?php

namespace AppBundle\Tests\Controller;

use AppBundle\Tests\TestBase;

class ArticleControllerTest extends TestBase
{
    public function testIndex()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/article/1');

        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $this->assertContains('Posted at', $crawler->filter('.date')->text());
        $this->assertContains('Total mark:', $crawler->filter('.like')->text());
        $this->assertContains('Tags:', $crawler->filter('.tag-cloud .title')->text());
        $this->assertContains('Top articles:', $crawler->filter('.top-articles .title')->text());
        $this->assertContains('Last comments:', $crawler->filter('.top-comments .title')->text());
    }
}
