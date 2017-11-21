# J-SMACSS: JFeatherz' SMACSS Framework

This SCSS + SMACSS framework is pretty complex. But each folder explains what goes in what.  Of particular note, check out `patterns/README.md` and `modules/README.md` for interesting best practices when using SCSS in an Object-Oriented way.  

Also, check out the __sample.scss files located in each directory, including this one!

## Documentation and Explanations

Sass: http://sass-lang.com/docs.html

Compass: http://compass-style.org/

SMACSS: http://smacss.com/book/

## A note on @import

### Compass Imports

1. *NEVER* use `@import 'compass'`.  This will import all of Compass' functions and mixins every single time
2. *ALWAYS* import only the mixins you need.

#### Example
    // BAD
    @import 'compass';

    // GOOD
    @import 'compass/css3/box-sizing';

    .element {
        @include box-sizing( border-box );
    }

### CSS-Internal Imports (Oxymoron)

Same rules apply here.  Example, only `@import 'variables'` when you need it.