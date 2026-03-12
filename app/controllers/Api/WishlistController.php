<?php

require_once(BASE."/app/model/wishlist.php");

class WishlistController
{
    private wishlist $wishlist;

    public function __construct()
    {
        $this->wishlist = new wishlist();
    }
    public function loadUserWishlist()
    {
        $this->wishlist->loadUserWishlist();
    }

    public function toggleWishlist()
    {
        $this->wishlist->toggleWishlist($_POST);
    }

    public function removeWishlistItem()
    {
        $this->wishlist->removeWishlistItem($_POST);
    }
}
