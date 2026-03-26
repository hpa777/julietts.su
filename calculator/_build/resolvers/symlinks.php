<?php
/** @var xPDOTransport $transport */
/** @var array $options */
/** @var modX $modx */
if ($transport->xpdo) {
    $modx =& $transport->xpdo;

    $dev = MODX_BASE_PATH . 'Extras/calculator/';
    /** @var xPDOCacheManager $cache */
    $cache = $modx->getCacheManager();
    if (file_exists($dev) && $cache) {
        if (!is_link($dev . 'assets/components/calculator')) {
            $cache->deleteTree(
                $dev . 'assets/components/calculator/',
                ['deleteTop' => true, 'skipDirs' => false, 'extensions' => []]
            );
            symlink(MODX_ASSETS_PATH . 'components/calculator/', $dev . 'assets/components/calculator');
        }
        if (!is_link($dev . 'core/components/calculator')) {
            $cache->deleteTree(
                $dev . 'core/components/calculator/',
                ['deleteTop' => true, 'skipDirs' => false, 'extensions' => []]
            );
            symlink(MODX_CORE_PATH . 'components/calculator/', $dev . 'core/components/calculator');
        }
    }
}

return true;