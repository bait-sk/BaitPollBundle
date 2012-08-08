Install instructions
====================

1. Install bundle
-----------------

Add the following to respective files:

**deps**

```
[BaitPollBundle]
    git=https://github.com/bait-sk/BaitPollBundle.git
    target=bundles/Bait/PollBundle
```

**app/autoload.php**


``` php
$loader->registerNamespaces(array(
    // ...
    'Bait' => __DIR__.'/../vendor/bundles',
));
```

**app/AppKernel.php**

``` php
public function registerBundles()
{
    $bundles = array(
        // ...
        new Bait\PollBundle\BaitPollBundle(),
    );
}
```

and install new vendor:

```
php bin/vendors install
```

2a. Add entities (ORM)
----------------------

**Poll**

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
     * @ORM\OneToMany(targetEntity="Field", mappedBy="poll")
     */
    protected $fields;

    /**
     * Add fields
     *
     * @param Field $fields
     */
    public function addField(Field $fields)
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

**Field**

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
     * @ORM\OrderBy({"position" = "ASC"})
     */
    protected $children;

    /**
     * @ORM\ManyToOne(targetEntity="Field", inversedBy="children")
     * @ORM\JoinColumn(name="parent_id", referencedColumnName="id")
     */
    protected $parent;

    /**
     * @ORM\ManyToOne(targetEntity="Poll")
     */
    protected $poll;
}
```

**AnswerGroup**
``` php
<?php

namespace Acme\DemoBundle\Entity;

use Bait\PollBundle\Entity\AnswerGroup as BaseAnswerGroup;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="poll_answer_group")
 */
class AnswerGroup extends BaseAnswerGroup
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * Poll.
     *
     * @ORM\ManyToOne(targetEntity="Acme\DemoBundle\Entity\Poll")
     */
    protected $poll;
}
```

**Answer**

``` php
<?php

namespace Acme\DemoBundle\Entity;

use Bait\PollBundle\Entity\Answer as BaseAnswer;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="poll_answer")
 */
class Answer extends BaseAnswer
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\ManyToOne(targetEntity="Field")
     */
    protected $field;

    /**
     * AnswerGroup.
     *
     * @ORM\ManyToOne(targetEntity="Acme\DemoBundle\Entity\AnswerGroup")
     */
    protected $answerGroup;
}
```

and add some config to `app/config.yml`:

``` yml
bait_poll:
    db_driver: orm
    #this is needed when you want to use upload field types
    upload_dir: %kernel.root_dir%/../web/poll
    poll:
        class: Acme\DemoBundle\Entity\Poll
    field:
        class: Acme\DemoBundle\Entity\Field
    answer:
        class: Acme\DemoBundle\Entity\Answer
    answer_group:
        class: Acme\DemoBundle\Entity\AnswerGroup
```

```
php app/console doctrine:schema:update --force
```

3. Add some poll
----------------

4. Render it
------------

**Controller**

``` php
$poll = $this->container->get('bait_poll.poll');
$poll->create(1);
```

**Template**

``` html
{{ poll.render()|raw }}
```

Integration with [FOSUserBundle](http://github.com/FriendsOfSymfony/FOSUserBundle)
==============================

* Install [FOSUserBundle](https://github.com/FriendsOfSymfony/FOSUserBundle)
* Implement `SignedAnswerGroupInterface` in your `AnswerGroup` entity / document:

``` php
<?php
...

use Bait\PollBundle\Model\SignedAnswerGroupInterface;

class AnswerGroup extends BaseAnswerGroup implements SignedAnswerGroupInterface
{
    /**
     * Author of answer.
     *
     * @ORM\ManyToOne(targetEntity="Acme\DemoBundle\Entity\User")
     */
    protected $author;

    public function setAuthor(UserInterface $author)
    {
        $this->author = $author;
    }

    public function getAuthor()
    {
        return $this->author;
    }

    ...
}
```

* Create your version of `AnswerGroupManager` that implements `SignedAnswerGroupManagerInterface`:

``` php
<?php

namespace Acme\DemoBundle\Entity;

use Bait\PollBundle\Entity\AnswerGroupManager as BaseAnswerGroupManager;
use Bait\PollBundle\Model\SignedAnswerGroupManagerInterface;
use Bait\PollBundle\Model\PollInterface;

class AnswerGroupManager extends BaseAnswerGroupManager implements SignedAnswerGroupManagerInterface
{
    /**
     * {@inheritDoc}
     */
    public function hasAnswered(PollInterface $poll)
    {
        return $this->hasAnsweredAnonymously($poll) || $this->hasUserAnswered($poll);
    }

    /**
     * {@inheritDoc}
     */
    public function hasUserAnswered(PollInterface $poll)
    {
        ...
    }
}
```

* Optionally, you can implement `SignedPollInterface` in your Poll entity / document if you need it.
