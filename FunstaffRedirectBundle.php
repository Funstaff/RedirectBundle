<?php

namespace Funstaff\Bundle\RedirectBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Funstaff\Bundle\RedirectBundle\DependencyInjection\CompilerPass\RedirectTwigLayoutPass;

/**
 * FunstaffRedirectBundle.
 *
 * @author Bertrand Zuchuat <bertrand.zuchuat@gmail.com>
 */
class FunstaffRedirectBundle extends Bundle
{
    public function build(ContainerBuilder $container)
    {
        parent::build($container);

        $container->addCompilerPass(new RedirectTwigLayoutPass());
    }
}
