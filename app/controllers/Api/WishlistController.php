<?php

require_once("../app/model/wishlist.php");

class WishlistController
{
    public function loadUserWishlist()
    {
        $wishlist = new wishlist();
        $wishlist->loadUserWishlist();
    }

    public function toggleWishlist()
    {
        $wishlist = new wishlist();
        $wishlist->toggleWishlist($_POST);
    }

    public function removeWishlistItem()
    {
        $wishlist = new wishlist();
        $wishlist->removeWishlistItem($_POST);
    }
}
