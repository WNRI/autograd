autograd is a result of [a project](http://www.vestforsk.no/en/projects/automatic-grading-of-hikes) by the [Western Norway Reasearch Institute](http://www.vestforsk.no) funded by [NCE Tourism – Fjord Norway](http://www.fjordnorway.com/no/NCE-Tourism/)
to test the feasability of automatically grading nature activity routes (first hiking) according to [the national (Norway) standard for hiking and grading](http://www.fjellturisme.no/skilting-og-gradering),
and the suitability of the standard for such an implementation of the standard.

Requirements / usage
===================

You need PHP and MySQL. Use the included `autograd.sql` to initialize the database, fill in your database information in `connect-db.php`.

License
======

The php scripts are copyright Western Norway Research Institute, see [[COPYING]] for more information.

We rely on [jQuery](http://jquery.com/), [Leaflet](http://leafletjs.com/), [Flot](http://www.flotcharts.org/) and [code](https://github.com/esisa/kresendoverktoy/blob/master/profilEksempel.html) from [Kresendo verktøy](http://verktoy.kresendo.no) for visualisation, some included as code, some simply referenced, but all with their own copyright and license.