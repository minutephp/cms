<?php
/**
 * Created by: MinutePHP framework
 */
namespace App\Controller\Theme {

    use App\Model\MTheme;
    use App\Model\MThemeAsset;
    use Minute\Cache\QCache;
    use Minute\Http\Browser;
    use Minute\Mime\MimeUtils;

    class LoadAsset {
        /**
         * @var QCache
         */
        private $cache;
        /**
         * @var Browser
         */
        private $browser;
        /**
         * @var MimeUtils
         */
        private $mimeUtils;

        /**
         * LoadAsset constructor.
         *
         * @param QCache $cache
         * @param Browser $browser
         * @param MimeUtils $mimeUtils
         */
        public function __construct(QCache $cache, Browser $browser, MimeUtils $mimeUtils) {
            $this->cache     = $cache;
            $this->browser   = $browser;
            $this->mimeUtils = $mimeUtils;
        }

        public function index(string $theme_name, string $asset_name) {
            $asset = $this->cache->get("asset-$theme_name-$asset_name", function () use ($theme_name, $asset_name) {
                if ($theme = MTheme::where('name', '=', $theme_name)->first()) {
                    /** @var MThemeAsset $asset */
                    if ($asset = MThemeAsset::where('name', '=', $asset_name)->first()) {
                        $result = $asset->attributesToArray();
                        if (!empty($result['url'])) {
                            $result['content'] = $this->browser->getUrl(sprintf('%s?%d', $result['url'], rand(1, 999999999)));

                            return $result;
                        }
                    }
                }

                return null;
            }, 600);

            if (!empty($asset['content'])) {
                $mime = $asset['type'];
                if ($mime == 'auto') {
                    $mime = $this->mimeUtils->getMimeType($asset['url']);
                }

                header("Content-type: $mime");
                print $asset['content'];
                exit(0);
            }

            header('404 Not found', true, 404);
            print("404 Not found. The resource you're looking for does not exists.");
        }
    }
}