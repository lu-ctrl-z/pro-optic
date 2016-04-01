<?php
App::uses('SiteController', 'Controller/Base');
/**
 * HomeController
 * @author Luvina
 * @access public
 * @see index()
 */
class HomeController extends SiteController {

    /**
     * URL: /
     * index
     * @author Luvina
     * @access public
     */
    public function index() {
        $this->set('title_layout' , '訪問看護ステーションナビ | 登録無料の訪問看護ステーション検索サイト。 ほかナビ。 訪看ナビ' );
        $this->set('description_layout' , 'お近くの訪問看護ステーションを簡単に検索できます。事業所の情報は無料で登録できます。在宅医療、在宅療養にご活用ください。' );
        $this->set('keywords_layout' , '訪問看護ステーションナビ,ほかナビ,訪看ナビ,検索,所在地,定休日,営業日時,看護師,PT,理学療法士,OT,作業療法士,ST,言語聴覚士' );
        // #149 Start Luvina Modify
        $aryDataNews = $this->getDataFromRss('rss_news');
        $aryDataInfo = $this->getDataFromRss('rss_info');
        $this->setApp('aryDataNews' , $aryDataNews);
        $this->setApp('aryDataInfo' ,$aryDataInfo);
        // #149 End Luvina Modify
        $this->hkRender('index', 'top');
    }
    /**
     * URL: /portal/udel/send
     * index
     * @author Luvina
     * @access public
     */
    public function uDelSend() {
        $this->set('title_layout' , '会員情報：削除 | 訪問看護ステーションナビ' );
        $this->set('description_layout' , '訪問看護ステーションナビ' );
        $this->set('keywords_layout' , '訪問看護ステーションナビ' );
        $this->hkRender('udel_send', 'portal');
    }
}