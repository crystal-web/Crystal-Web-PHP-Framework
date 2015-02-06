<?php
class BlogSlider extends Model {
    private $html;
    private $dbobj;

    public function getSlider($nb = 3) {
        $nb = (int) $nb;
        /* Jointure de deux table
        $pre = $this->pdo->prepare("SELECT `id`, `uid`, `comment`, `time`, `picture`, `title`, `content`, 'blog' `istable`
FROM  `azre__Blog`
UNION
        SELECT `id`, `uid`, `comment`, `time`, `picture`, `title`, `content`, 'event' `istable`
FROM  `azre__Event`
ORDER BY time DESC
LIMIT 0, 3");//*/

        $this->setTable('Blog');
        $this->dbobj = $this->find(
            array(
                'fields' => '`id`, `uid`, `comment`, `time`, `picture`, `title`',
                'order' => 'id DESC',
                'limit' => '0, '  . $nb,
                'conditions' => "`picture` !=  '' AND  `slider` =1"
            ));
        $this->makeHtml();
        return $this->html;
    }

    private function makeHtml() {
        $carouselId = "myCarousel";
        $this->html = new Html();
        $this->html
            ->section(array('id' => 'dg-container', 'class' => 'dg-container'))
                ->div(array('class' => 'dg-wrapper ajax'));

                    for($i=0;$i<count($this->dbobj);$i++) {
                        $this->html
                            ->a(array('href' => Router::url('actu/page:'.clean($this->dbobj[$i]->title, 'slug').'/id:' . $this->dbobj[$i]->id).'#content'))
                                ->img(array('src' => '/assets/images/blog/' . clean($this->dbobj[$i]->picture, 'str')))
                                ->div(array('class' => 'well'), clean($this->dbobj[$i]->title, 'str'))->end()
                            ->end();
                    }

        $this->html
                ->end()
            ->end();
    }
}
/*

<div id="myCarousel" class="carousel slide">


    <!-- Wrapper for slides -->
    <div class="carousel-inner">
        <div class="item active">
            <div class="fill" style="background-image:url('http://imagineyourcraft.fr/files/images/slider/1380661204-glacier.jpg');"></div>
            <div class="carousel-caption">
                <h1>A Full-Width Image Slider Template</h1>
            </div>
        </div>
        <div class="item">
            <div class="fill" style="background-image:url('http://placehold.it/1900x1080&text=Slide Two');"></div>
            <div class="carousel-caption">
                <h1>Caption 2</h1>
            </div>
        </div>
        <div class="item">
            <div class="fill" style="background-image:url('http://placehold.it/1900x1080&text=Slide Three');"></div>
            <div class="carousel-caption">
                <h1>Caption 3</h1>
            </div>
        </div>
    </div>

    <!-- Controls -->
    <a class="left carousel-control" href="#myCarousel" data-slide="prev">
        <span class="icon-prev"></span>
    </a>
    <a class="right carousel-control" href="#myCarousel" data-slide="next">
        <span class="icon-next"></span>
    </a>
</div>*/