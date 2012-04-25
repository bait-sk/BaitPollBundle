BaitPollBundle
==============

This bundle provides support for various types of polls and competitions for Symfony2.

[![Build Status](https://secure.travis-ci.org/bait-sk/BaitPollBundle.png?branch=master)](http://travis-ci.org/bait-sk/BaitPollBundle)


Features
--------

- almost none at the moment


Todo
----

- at least some features


Quick install
-------------

Add the following to your `deps` file:

```
[BaitPollBundle]
    git=https://github.com/bait-sk/BaitPollBundle.git
    target=bundles/Bait/PollBundle
```

``` bash
$ php bin/vendors install
```

``` php
<?php
// app/autoload.php

$loader->registerNamespaces(array(
    // ...
    'Bait' => __DIR__.'/../vendor/bundles',
));
```

``` php
<?php
// app/AppKernel.php

public function registerBundles()
{
    $bundles = array(
        // ...
        new Bait\PollBundle\BaitPollBundle(),
    );
}
```

``` php
<?php

namespace Acme\DemoBundle\Entity;

use Bait\PollBundle\Entity\Poll as BasePoll;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="poll")
 */
class Poll extends BasePoll
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\OneToMany(targetEntity="Acme\DemoBundle\Entity\Field", mappedBy="poll")
     */
    protected $fields;

    /**
     * Add fields
     *
     * @param Acme\DemoBundle\Entity\Field $fields
     */
    public function addField(\Acme\DemoBundle\Entity\Field $fields)
    {
        $this->fields[] = $fields;
    }

    /**
     * Get fields
     *
     * @return Doctrine\Common\Collections\Collection
     */
    public function getFields()
    {
        return $this->fields;
    }

}
```

``` php
<?php

namespace Acme\DemoBundle\Entity;

use Bait\PollBundle\Entity\Field as BaseField;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="poll_field")
 */
class Field extends BaseField
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\OneToMany(targetEntity="Field", mappedBy="parent")
     */
    protected $children;

    /**
     * @ORM\ManyToOne(targetEntity="Field", inversedBy="children")
     * @ORM\JoinColumn(name="parent_id", referencedColumnName="id")
     */
    protected $parent;

    /**
     * @ORM\ManyToOne(targetEntity="Acme\DemoBundle\Entity\Poll")
     */
    protected $poll;
}
```

```
bait_poll:
    resource: "@BaitPollBundle/Resources/config/routing.yml"
    prefix: /poll
```
