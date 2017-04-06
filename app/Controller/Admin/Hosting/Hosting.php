<?php
/**
 * Created by: MinutePHP framework
 */

namespace App\Controller\Admin\Hosting {

    use Aws\S3\S3Client;
    use Minute\Aws\Client;
    use Minute\CDN\UrlResolver;
    use Minute\Config\Config;
    use Minute\Event\ImportEvent;
    use Minute\Routing\RouteEx;
    use Minute\View\View;

    class Hosting {
        /**
         * @var S3Client
         */
        protected $s3;
        /**
         * @var Config
         */
        private $config;
        /**
         * @var UrlResolver
         */
        private $resolver;

        /**
         * Hosting constructor.
         *
         * @param Config $config
         * @param Client $client
         * @param UrlResolver $resolver
         */
        public function __construct(Config $config, Client $client, UrlResolver $resolver) {
            $this->config   = $config;
            $this->s3       = $client->getS3Client();
            $this->resolver = $resolver;
        }

        public function listFiles(ImportEvent $event) {
            $path   = ltrim($this->config->get(Client::AWS_KEY . '/hosting', 'hosting'), '/');
            $s3     = $this->s3;
            $bucket = $this->config->get(Client::AWS_KEY . '/uploads/upload_bucket', ('www' . $this->config->getPublicVars('domain')));

            if ($objects = $s3->getIterator('ListObjects', array('Bucket' => $bucket, 'Prefix' => $path))) {
                foreach ($objects as $object) {
                    if ($object['Size'] > 0) {
                        $url       = $this->resolver->getUploadUrl($bucket, $object['Key']);
                        $results[] = ['url' => $url, 'name' => basename($url)];
                    }
                }
            }

            $event->setContent($results ?? []);
        }

        public function upload(string $_mode, array $_models, RouteEx $_route, array $_parents) {
            //TODO: do things with $_models
        }
    }
}