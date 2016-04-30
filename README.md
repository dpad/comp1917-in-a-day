# COMP1917 In A Day

[COMP1917 In A Day](http://comp1917.dpadilha.com) is an educational website designed to teach the basics of programming in C. 

It was specifically created to cater for [University of New South Wales (UNSW Australia)](http://www.unsw.edu.au) students taking the first-year computer science course, [COMP1917 - Computing 1](https://wiki.cse.unsw.edu.au/info/COMP1917). 

The aim of this project is simple:
* To explain aspects of programming in [C](https://en.wikipedia.org/wiki/C_(programming_language)) (the programming language taught in COMP1917).
* To teach in a manner that is easy-to-understand.
* To keep students engaged in learning to program by utilising inappropriate jokes.

The website is currently hosted at [http://comp1917.dpadilha.com](http://comp1917.dpadilha.com) using [Google App Engine](https://cloud.google.com/appengine/docs).

## Disclaimers

**This is an unoffical, unendorsed guide** to the COMP1917 course. This guide is written by former students based on their own experiences of the course, and may thus become increasingly irrelevant over the course of time. Everything in this guide should be taken as **advice only**. When in doubt, **consult your tutor and/or lecturer**.

This guide contains **strong language**, **parody machismo**, **outright assertions of superiority over "trendy" subcultures**, **blatant sexist and discriminatory remarks**, and other acts which may be perceived as **inappropriate**. If you are someone who may be offended by such behaviour, you are advised not to engage with this project in any way.

To provide a bit of history, the vast majority of the (actually useful) content of this guide was originally written by [Stephen Sherratt](https://github.com/stevebob). The less-useful bits, including the website itself, the horrible templating engine, and all the blatantly inappropriate stuff, was done by [Dan Padilha](https://github.com/dpad). So you know who to blame if you're truly offended.

## Contributing changes

*Anyone may contribute changes to this project*, whether to point out or correct mistakes, or
simply to improve how a specific suject is taught.

To contribute, you must have a [GitHub account](https://github.com/). Simply [fork this repository](https://github.com/dpad/comp1917-in-a-day#fork-destination-box) (by clicking on "Fork" on the top-right of this page) into your personal GitHub account, make some changes, then open a [pull request](https://github.com/dpad/comp1917-in-a-day/pulls) detailing the changes you have made. The owners of this repository will review each pull request and will merge any proposed changes on to the [live website](http://comp1917.dpadilha.com).

If you don't understand what any of that means, you'll need to use [the Google](http://lmgtfy.com/?q=how+do+I+contribute+to+a+github+project).

The actual content of the course is stored in the `chapters` directory. Each chapter (as listed in `chapters/chapterslist`) has its own directory containing example code as well as sections (listed in `chapers/some_chapter/sectionslist`), which are files named `something.section`.

Each section is parsed by an amazingly archaic templating engine, which was made before [Markdown](https://en.wikipedia.org/wiki/Markdown) and [reStructuredText](https://en.wikipedia.org/wiki/ReStructuredText) were cool. Just follow the existing examples to see how it works. Maybe you could even implement a Markdown/rST template reader for me or something. I dunno. 

## Copyright and License

COMP1917 In A Day is intended as an educational resource. With that in mind, anyone is allowed to copy, edit, and even host their own copies of it, but:
* You **must** publicly release the source code of any changes you make.
* You **must** acknowledge and attribute any authors who have previously contributed to this project.
* You **must not** be a dick by pretending that you made this entire thing through your own sweat and tears just so you can impress chicks. We already tried that and it didn't work.

```
    COMP1917 In A Day is an educational website designed to teach the basics of
    programming in C.

    Copyright (C) 2011-2016  Dan Padilha, Stephen Sherratt

    This program is free software: you can redistribute it and/or modify it
    under the terms of the GNU Affero General Public License as published by
    the Free Software Foundation, either version 3 of the License, or (at your
    option) any later version.

    This program is distributed in the hope that it will be useful, but WITHOUT
    ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or
    FITNESS FOR A PARTICULAR PURPOSE.  See the GNU Affero General Public
    License for more details.

    You should have received a copy of the GNU Affero General Public License
    along with this program.  If not, see <http://www.gnu.org/licenses/>.
```
