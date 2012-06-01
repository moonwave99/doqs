#Doqs - a simple Markdown Documents Repository.

You need a simple way to *write* down your documents and specs, as you need a nice way to *present* them - just follow the [Markdown](http://daringfireball.net/projects/markdown/) path. In a github fashion I wrote a very simple repository for MD files you can put on your webserver so you can share documents between mates. I used [this nice PHP MD parsing library](http://michelf.com/projects/php-markdown) by **Michel Fortin** and ubiquitous [Bootstrap](http://twitter.github.com/bootstrap/).

Well, [see Doqs in action!](http://moonwave99.webfactional.com/doqs/)

---

##Installation and Configuration

Just clone this repository into desired webserver path:

	$ git clone https://github.com/moonwave99/doqs

or unzip in same location if you prefer. Then in ```index.php``` you have to config a bunch of constants:

	define('BASE_PATH', 'http://www.somesite.com/some/path/');
	define('DOCS_PATH', __DIR__ . "/docs/");
	define("REPO_NAME", 'Doqs');
	define("REPO_DESC", 'Markdown document repository.');

being:

* the base **URL** for the repo [could be ```http://localhost/``` of course];
* the actual file folder path;
* the repo name - will be displayed on the mainbar;
* the repo desc - just a brief description on the main page.

***Notice** : if you want your docs to stay secure, consider protecting the folder somehow [with [basic http auth](http://httpd.apache.org/docs/2.0/howto/auth.html) for example].

---

##Usage

Point your browser to the ```BASE_PATH```, then read your docs - not very much more to say.

---

##Copyright and license

Copyright (c) 2012 MWLabs

Permission is hereby granted, free of charge, to any person obtaining a copy of this software and associated documentation files (the "Software"), to deal in the Software without restriction, including without limitation the rights to use, copy, modify, merge, publish, distribute, sublicense, and/or sell copies of the Software, and to permit persons to whom the Software is furnished to do so, subject to the following conditions:

The above copyright notice and this permission notice shall be included in all copies or substantial portions of the Software.

THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM, OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE SOFTWARE.

---

##Author

[www.diegocaponera.com](http://www.diegocaponera.com/) - Just another coder.