<?php

namespace AppBundle\Repository;

use Doctrine\ORM\Tools\Pagination\Paginator;

class PostRepository extends \Doctrine\ORM\EntityRepository
{
    public function getPosts($page, $maximum_posts)
    {

        $query = $this->_em->createQueryBuilder()
            ->select('post')
            ->from('AppBundle:Post','post')
            ->orderBy('post.published','DESC');

        $query->setFirstResult(($page - 1) * $maximum_posts)
            ->setMaxResults($maximum_posts);

        return new Paginator($query);
    }

    public function getPostsLike($title, $page, $maximum_posts){

        $query = $this->_em->createQueryBuilder()
            ->select('post')
            ->from('AppBundle:Post','post')
            ->where('lower(post.title) LIKE lower(:title)')
            ->setParameter('title', '%'.$title.'%')
            ->orderBy('post.published','DESC');

        $query->setFirstResult(($page - 1) * $maximum_posts)
            ->setMaxResults($maximum_posts);

        return new Paginator($query);
    }
}
