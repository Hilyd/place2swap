<?php


namespace App\Utilities;

use App\Entity\Article;
use Elasticsearch\ClientBuilder;


class Elasticsearch
{
    private $index = 'place2swap';

    private $type = '_doc';

    private $host;

    private $port;

    private $connecting;

    private $match = '';

    private $from = 0;

    private $size = 10;

    private $total = 0;

    public function __construct($host, $port, $index, $type)
    {
        $this->setHost($host);
        $this->setPort($port);
        $this->setIndex($index);
        $this->setType($type);
        $this->connecting();
    }

    /**
     * @return string
     */
    public function getIndex(): string
    {
        return $this->index;
    }

    /**
     * @param string $index
     */
    public function setIndex(string $index)
    {
        $this->index = $index;
        return $this;
    }

    /**
     * @return string
     */
    public function getType(): string
    {
        return $this->type;
    }

    /**
     * @param string $type
     */
    public function setType(string $type)
    {
        $this->type = $type;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getHost()
    {
        return $this->host;
    }

    /**
     * @param mixed $host
     */
    public function setHost($host)
    {
        $this->host = $host;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getPort()
    {
        return $this->port;
    }

    /**
     * @param mixed $port
     */
    public function setPort($port)
    {
        $this->port = $port;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getConnecting()
    {
        return $this->connecting;
    }

    /**
     * @param mixed $connecting
     */
    public function setConnecting($connecting)
    {
        $this->connecting = $connecting;
        return $this;
    }

    /**
     * @return string
     */
    public function getMatch(): string
    {
        return $this->match;
    }

    /**
     * @param string $match
     */
    public function setMatch(string $match)
    {
        $this->match = $match;
        return $this;

    }

    /**
     * @return int
     */
    public function getFrom(): int
    {
        return $this->from;
    }

    /**
     * @param int $from
     */
    public function setFrom(int $from)
    {
        $this->from = $from;
        return $this;
    }

    /**
     * @return int
     */
    public function getSize(): int
    {
        return $this->size;
    }

    /**
     * @param int $size
     */
    public function setSize(int $size)
    {
        $this->size = $size;
        return $this;
    }

    /**
     * @return int
     */
    public function getTotal(): int
    {
        return $this->total;
    }

    /**
     * @param int $total
     */
    public function setTotal(int $total)
    {
        $this->total = $total;
        return $this;
    }

    public function connecting()
    {
        $host = [
            'host' => $this->getHost(),
            'port' => $this->getPort()
        ];
        //dump($host);exit;
        $this->connecting = ClientBuilder::create()->setHosts([$host])->build();

    }

    public function index(Article $article)
        // Indexer un article
    {
        $params = [
            'index' => $this->getIndex(),
            'type' => $this->getType(),
            'id' => $article->getId(),
            'body' => $article->toArray(),
        ];
        return $this->getConnecting()->index($params);
    }

    public function get(string $by, string $id): ?Article
    // trouver un article par son _id elasticsearch
    {
        $params = [
            'index' => $this->getIndex(),
            'type' => $this->getType(),
            'from' => 0,
            'size' => 1,
            'body' => ['query' => ['term' => [$by => $id]]]
        ];
        try {
            $resultElastic = $this->getConnecting()->search($params);
        } catch (Exception $e) {
            return null;
        }

        if (isset($resultElastic['hits']['total']) && $resultElastic['hits']['total'] == 1) {
            $article=  new Article($resultElastic['hits']['hits'][0]['_source']);
            $article->setId($resultElastic['hits']['hits'][0]['_id']);

            return $article;
        }

        return null;
    }

    public function search()

        // Chercher tous les articles dans l'index place2swap
    {
        $params = [
            'index' => $this->getIndex(),
            'type' => $this->getType(),
            'from' => $this->getFrom(),
            'body' => [
                'query' => [
                    'match_all' => new \stdClass()
                ]
            ]
        ];

        $arrayArticles = [];
        $resultElastic = $this->getConnecting()->search($params);

        if (isset($resultElastic['hits']['total']) && $resultElastic['hits']['total'] > 0) {

            foreach ($resultElastic['hits']['hits'] as $result) {
                $article = new Article($result['_source']);
                $article->setId($result['_id']);
                $arrayArticles[] = $article;
            }
        }
        return  $arrayArticles;

    }


}