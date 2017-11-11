
//
// Yurii K. 11.03.2016
//
var MyDemo = {
	// news to open on scroll
	links: [
		[ 'http://censor.net.ua/news/378495/partiyinyyi_biznes_i_oligarhi_doljny_byt_otstraneny_ot_gosudarstvennyh_resheniyi_lutsenko', '#00FF00' ],
		[ 'http://censor.net.ua/news/378496/pravoohraniteli_zaderjali_boyitsa_tornado_tsukura', '#FF0000' ],
		[ 'http://censor.net.ua/news/378492/ukraintsy_podali_v_mirovye_sudy_bolee_700_iskov_protiv_rossii_po_zaschite_svoih_prav_sovetnik_glavy', '#0000FF' ]
	],
	// changable
	link_index: 0, // increase link index to not load already loaded articles
	event_loadingArticle: false, // limit to one page load at time
	//
	// change color of article to see borders
	//
	colorArticle: function(article) {
		article.style['background-color'] = this.links[this.link_index][1];
	},
	//
	// check if provided articles was strolled down (bottom border is in offset)
	//
	isArticleScrolledDown: function(article){
		var offset = window.pageYOffset || document.documentElement.scrollTop;
		var lastArticleBottom = article.getBoundingClientRect().bottom + window.pageYOffset;
		return (offset > lastArticleBottom);
	},
	//
	// returns last article on the page. NOT optimized, traverses DOM on each call
	//
	getLastArticle: function()
	{
		var articles = document.getElementsByTagName("article");
		return articles[articles.length-1];
	},
	//
	// parses and returns article element from html data
	//
	parseArticle: function(pageHTML)
	{
		var parser = new DOMParser();
		var page = parser.parseFromString(pageHTML, "text/html");
		var article = page.getElementsByTagName("article")[0];
		return article;
	},
	// 
	// loads new article via AJAX
	//
	// depends:
	//		@var event_loadingArticle
	//		@var link_index
	//
	loadArticle: function(uri)
	{
		var xhr = new XMLHttpRequest();
		xhr.open('GET', uri, true); //async
		xhr.onreadystatechange = function() {
			if (xhr.readyState == 4) { //complete
				if(xhr.status == 200) { // ok
					var lastArticle = this.getLastArticle();
					var article = this.parseArticle(xhr.responseText);
					this.colorArticle(article);
					lastArticle.parentNode.insertBefore(article, lastArticle.nextSibling);
					
					this.event_loadingArticle = false;
					this.link_index++;
					console.log('article', article);
				}
			}
		}.bind(this);
		xhr.send();
	},
	//
	// main entry point. call once
	//
	init: function() {
		//color first article with custom color
		this.getLastArticle().style['background-color'] = '#FFFF00';
		// main event
		window.onscroll = function() {
			if(!this.event_loadingArticle && this.isArticleScrolledDown(this.getLastArticle()) && this.links.length > this.link_index ) {
				this.event_loadingArticle = true;
				var uri = this.links[this.link_index][0];
				console.log('load article: '+uri);
				this.loadArticle(uri);
				return;
			}
			//console.log('nothing to do');
		}.bind(this)
	}
};


MyDemo.init();
