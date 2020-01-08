<?php
namespace RallyShop\Models;

class Wishlists extends \Shop\Models\Wishlists
{
    public $user_id = null;
    public $session_id = null;
    public $items = array(); // array of \Shop\Models\Prefabs\CartItem objects, for easy copying to/from wishlist
    public $name = null; // user-defined name for wishlist
    public $items_count = null;

    protected $__collection_name = 'shop.wishlists';
    protected $__type = 'shop.wishlists';
    protected $__config = array(
        'default_sort' => array(
            'metadata.created.time' => - 1
        )
    );

    protected function fetchConditions()
    {
        parent::fetchConditions();

        $this->setCondition( 'type', $this->__type );

        $filter_user = $this->getState( 'filter.user' );
        if (strlen( $filter_user ))
        {
            $this->setCondition( 'user_id', new \MongoDB\BSON\ObjectID( (string) $filter_user ) );
        }

        $filter_has_items = $this->getState( 'filter.has_items' );
        if (strlen( $filter_has_items ))
        {
            if (empty($filter_has_items))
            {
                $this->setCondition( 'items_count', array( '$in' => array( 0, null ) ) );
            }
            else
            {
                $this->setCondition( 'items_count', array( '$nin' => array( 0, null ) ) );
            }

        }

        return $this;
    }

    /**
     * Get the current user's wishlist, either based on session_id (visitor) or user_id (logged-in)
     *
     * @return \Shop\Models\Wishlists
     */
    public static function fetch()
    {
        $identity = \Dsc\System::instance()->get( 'auth' )->getIdentity();
        if (empty( $identity->id ))
        {
            $wishlist = static::fetchForSession();
        }
        else
        {
            $wishlist = static::fetchForUser();
        }

        return $wishlist;
    }

    /**
     * Get the current session's wishlist
     *
     * @return \Shop\Models\Wishlists
     */
    public static function fetchForSession()
    {
        $wishlist = new static();

        $session_id = \Dsc\System::instance()->get( 'session' )->id();

        $wishlist->load( array(
            'session_id' => $session_id
        ) );

        $wishlist->session_id = $session_id;

        $wishlist->save();

        return $wishlist;
    }

    /**
     * Get the current user's wishlist
     *
     * @return \Shop\Models\Wishlists
     */
    public static function fetchForUser()
    {
        $wishlist = new static();

        $identity = \Dsc\System::instance()->get( 'auth' )->getIdentity();
        $session_id = \Dsc\System::instance()->get( 'session' )->id();

        if (! empty( $identity->id ))
        {
            $wishlist->load( array(
                'user_id' => new \MongoDB\BSON\ObjectID( (string) $identity->id )
            ) );
            $wishlist->user_id = $identity->id;

            $session_wishlist = static::fetchForSession();

            // if there was no user wishlist but there IS a session wishlist, just add the user_id to the session wishlist and save it
            if (empty( $wishlist->id ) && ! empty( $session_wishlist->id ))
            {
                $wishlist = $session_wishlist;
                $wishlist->user_id = $identity->id;
                $wishlist->save();
            }

            // if there was a user wishlist and there is a session wishlist, merge them and delete the session wishlist
            // if we already did the merge, skip this
            $session_wishlist_merged = \Dsc\System::instance()->get( 'session' )->get( 'shop.session_wishlist_merged' );
            if (! empty( $session_wishlist->id ) && $session_wishlist->id != $wishlist->id && empty( $session_wishlist_merged ))
            {
                $wishlist->session_id = $session_id;
                $wishlist->merge( $session_wishlist->cast() );
                $session_wishlist->deleteDocument();
                \Dsc\System::instance()->get( 'session' )->set( 'shop.session_wishlist_merged', true );
            }

            if (empty( $wishlist->id ))
            {
                $wishlist->save();
            }
        }

        return $wishlist;
    }

    /**
     * Gets the associated user object
     *
     * @return unknown
     */
    public function user()
    {
        $user = (new \Users\Models\Users)->load(array('_id'=>$this->user_id));

        return $user;
    }

    /**
     * Adds an item to the wishlist
     *
     * @param string $variant_id
     * @param \Shop\Models\Products $product
     * @param array $post
     */
    public function addItem( $variant_id,\Shop\Models\Products $product, array $post )
    {

    	$wishlistitem = \RallyShop\Models\Carts::createItem($variant_id, $product,  $post);

    	// Is the item already in the wishlist?
    	// if so, inc quantity
    	// otherwise add the wishlistitem
    	$exists = false;
    	foreach ( $this->items as $key => $item )
    	{
    		if ($item['hash'] == $wishlistitem->hash)
    		{
    			$exists = true;
    			$wishlistitem->id = $item['id'];
    			$wishlistitem->quantity = $wishlistitem->quantity + $item['quantity'];
    			$this->items[$key] = $wishlistitem->cast();

    			break;
    		}
    	}

    	if (! $exists)
    	{
    		$this->items[] = $wishlistitem->cast();
    	}

    	return $this->save();
    }

    /**
     * Determine if a user has added a variant to any of their wishlists
     *
     * @param unknown $variant_id
     * @param unknown $user_id
     */
    public static function hasAddedVariant( $variant_id, $wishlist_id )
    {
    	if (empty($wishlist_id)) {
    		return false;
    	}

    	return (new static)->collection()->findOne( array(
    			'items.variant_id' => $variant_id,
    			'_id' => new \MongoDB\BSON\ObjectID( (string) $wishlist_id )
    	) );
    }


    /**
     *
     * @param unknown $variant_id
     * @param string $wishlist_id
     */
    public function moveToCart( $wishlistitem_hash, $cart )
    {
        $item = $this->fetchItemByHash( $wishlistitem_hash );
        if (empty($item['id'])) {
            throw new \Exception( 'Invalid Wishlist Item' );
        }

        $variant_id = $item['variant_id'];
        $product = (new \Shop\Models\Variants)->getById($variant_id);

        $post = [];
        if ($product->{'tracking.model_number'} == 'RSD 50101') {
            $post['email'] = $item['email'];
        }

        if ($cart->addItem( $variant_id, $product, $post))
        {
            $this->removeItem( $wishlistitem_hash );
        }

        return $this;
    }

}
