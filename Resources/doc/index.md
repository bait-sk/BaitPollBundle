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

**Vote**

``` php
<?php

namespace Acme\DemoBundle\Entity;

use Bait\PollBundle\Entity\Vote as BaseVote;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="poll_vote")
 */
class Vote extends BaseVote
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
}
```

and add some config to `app/config.yml`:

``` yml
bait_poll:
    db_driver: orm
    poll:
        class: Acme\DemoBundle\Entity\Poll
    field:
        class: Acme\DemoBundle\Entity\Field
    vote:
        class: Acme\DemoBundle\Entity\Vote
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
* Implement `SignedVoteInterface` in your `Vote` entity / document:

``` php
<?php
...

use Bait\PollBundle\Model\SignedVoteInterface;

class Vote extends BaseVote implements SignedVoteInterface
{
    /**
     * Voter.
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

* Create your version of `VoteManager` that implements `SignedVoteManagerInterface`:

``` php
<?php

...

use Bait\PollBundle\Entity\VoteManager as BaseVoteManager;
use Bait\PollBundle\Model\SignedVoteManagerInterface;
use Bait\PollBundle\Model\PollInterface;

class VoteManager extends BaseVoteManager implements SignedVoteManagerInterface
{
    /**
     * {@inheritDoc}
     */
    public function hasVoted(PollInterface $poll)
    {
        return $this->hasVotedAnonymously($poll) || $this->hasUserVoted($poll);
    }

    /**
     * {@inheritDoc}
     */
    public function hasUserVoted(PollInterface $poll)
    {
        ...
    }
}
```
