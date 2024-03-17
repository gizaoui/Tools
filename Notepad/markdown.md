# MARKDOWN VIEWER ++

- [1. H2](#H2)
	- [1.1. H3](#H3)
		- [1.1.1. H4](#H4)
			- [1.1.1.1. H5](#H5)
				- [1.1.1.1.1. H6](#H6)
- [2. Table](#Table)
- [3. Blockquotes](#Blockquotes)


<br>


## [1. H2](#MARKDOWN-VIEWER)

fdfd

fdfd

### [1.1. H3](#MARKDOWN-VIEWER)

fdfd

fdfd

#### [1.1.1. H4](#MARKDOWN-VIEWER)

fdfd

fdfd

##### [1.1.1.1. H5](#MARKDOWN-VIEWER)

fdfd

fdfd

###### [1.1.1.1. H6](#MARKDOWN-VIEWER)

Here is a simple footnote[^1].

A footnote can also have multiple lines[^2].

You can also use words, to fit your writing style more closely[^note].


[Readme](file://Readme.md)

+ `*italic*` &rightarrow; *italic*
+ `**bold**` &rightarrow; **bold**
+ `~strike~` &rightarrow; ~strike~
+ `<u>dsdsdsd</u>` &rightarrow; <u>dsdsdsd</u>
+ `~~Scratch this~~` &rightarrow; ~~Scratch this~~

<br>

Emphasis, aka italics, with *asterisks* or _underscores_.

Strong emphasis, aka bold, with **asterisks** or __underscores__.

Combined emphasis with **asterisks and _underscores_**.

Strikethrough uses two tildes. ~~Scratch this~~


&phone; 4 554 5454 



<span>Span *will* work.</span>

Some **bold** Some *italic* and [a link][1] 

![alt text][2]

[1]: http://www.google.com
[2]: http://www.google.com/intl/en_ALL/images/logo.gif
  

`TODO : A FAIRE`

```
body {
	font-family: Helvetica, arial, sans-serif;
	font-size: 12px;
	margin:0;
	padding: 0;
}

h1 {
	text-align: center;
	font-size: 30px;
	background-color: #F6F6F6;
	margin: 0 0 30px 0;
	padding: 15px 0 25px 0;
}

h2, h3, h4, h5, h6 {
	border-bottom: 1px solid #E8E8E8;
}

h2 {
	margin: 35px 20px 15px 25px;
	font-size: 22px;
}

h3 {
	margin: 25px 20px 15px 35px;
	font-size: 20px;
}

h4 {
	margin: 25px 20px 15px 45px;
	font-size: 18px;
}

h5 {
	margin: 25px 20px 15px 55px;
	font-size: 16px;
}

h6 {
	margin: 25px 20px 15px 64px;
	font-size: 14px;
}

p {
	margin: 4px 20px 4px 25px;
	font-size: 14px;
}

pre {
	white-space: nowrap;
	background-color: #f8f8f8;
	border: 1px solid #585656;
	font-size: 13px;
	margin: 10px 20px 10px 25px;
	padding: 10px;
	font-style: italic;
}

pre code {
	display: block;
	white-space: pre;
	margin: 0;
	padding: 0;
	border: none;
}

p code {
	margin: 5px 0 5px 0;
	padding: 2px 5px 2px 5px;
	font-weight: bold;
	background-color: #ff4040;
}

code.language-sql {
	color: blue;
	font-style: italic;
}

ul {
	padding: 0;
	margin: 20px 0 5px 35px;
}

li {
	padding:0;
	margin: 0;
	list-style-type:disc;
}

li ul {
	padding: 0;
	margin: 1px 0 1px 10px;
}

li a {
	font-size: 12px;
}

table {
	border-collapse: collapse;
	border: 1px solid black;
	margin: 4px 20px 20px 25px;
}

th, td {
	border: 1px solid black;
}

th {
	background-color: #c0c0c0;
}

th, td {
	padding: 3px 12px;
}

a {
	color: #000000;
	text-decoration: none;
}

blockquote {
	border-left: 2px solid #dddddd;
	font-style: italic;
	padding: 0 15px;
	color: #777777;
	margin: 15px 0;
}

img {
	max-width: 100%
}
```

## [2. Table](#MARKDOWN-VIEWER)

Colons can be used to align columns.

| Tables        | Are           | Cool  |
| ------------- |:-------------:| -----:|
| col 3 is      | right-aligned | $1600 |
| col 2 is      | centered      |   $12 |
| zebra stripes | are neat      |    $1 |

There must be at least 3 dashes separating each header cell.
The outer pipes (|) are optional, and you don't need to make the 
raw Markdown line up prettily. You can also use inline Markdown.

Markdown | Less | Pretty
--- | --- | ---
*Still* | `renders` | **nicely**
1 | 2 | 3


## [3. Blockquotes](#MARKDOWN-VIEWER)


> Blockquotes are very handy in email to emulate reply text.
> This line is part of the same quote.

Quote break.

> This is a very long line that will still be quoted properly when it wraps. Oh boy let's keep writing to make sure this is long enough to actually wrap for everyone. Oh, you can *put* **Markdown** into a blockquote.






[^1]: My reference.
[^2]: Every new line should be prefixed with 2 spaces.
  This allows you to have a footnote with multiple lines.
[^note]: Named footnotes will still render with numbers instead of the text but allow easier identification and linking.
    This footnote also has been made with a different syntax using 4 spaces for new lines.
	
	
	
