<?php

namespace Management\Session\UserBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;

class ManagementSessionUserBundle extends Bundle
{
	public function getParent()
	{
		return 'FOSUserBundle';
	}
}
