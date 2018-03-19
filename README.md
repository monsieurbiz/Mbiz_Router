Mbiz Router for Magento 2
=================

## Introduction

Your routes on Magento 2

## Installation

```
{
    "repositories": [
        {
            "type": "vcs",
            "url": "https://github.com/monsieurbiz/Mbiz_Router.git"
        }
    ],
    "require": {
        "monsieurbiz/Mbiz_Router": "master"
    }
}
```

## Static URL
*   URL: `http://example.org/foo.hmtl`
*   Match: `http://example.org/foo/index/index`
*   `config.xml`:
```xml
<default>
    <mbiz_router>
        <my_router>
            <type>static</type>
            <route>foo.html</route>
            <module>foo</module>
            <controller>index</controller>
            <action>index</action>
        </my_router>
    </mbiz_router>
</default>
```

## Regex URL
*   URL: `http://example.org/foo.hmtl`
*   Match: `http://example.org/foo/index/index`
*   `config.xml`:
```xml
<default>
    <mbiz_router>
        <my_router>
            <type>regex</type>
            <route>helloworld/(foo|bar|baz)\.html</route>
            <reverse>helloworld/%1$s.html</reverse>
            <map>
                <key>1</key>
            </map>
            <module>hello</module>
            <controller>index</controller>
            <action>index</action>
        </my_router>
    </mbiz_router>
</default>
```

---
*On the road again*