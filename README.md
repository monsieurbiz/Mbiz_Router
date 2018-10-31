# Router for Magento 2

## Introduction

Your routes on Magento 2.

## Installation

```
composer require monsieurbiz/mbiz_router=dev-master
```

## Usage

### Static URL

*   URL: `http://example.org/foo.html`
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

### Regex URL

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

## Contribute

Please enjoy creating Pull Requests.

## License

This source code is provided under MIT License.

## Authors

Originally created by [@jacquesbh](https://github.com/jacquesbh/jbh_router), we made it for Magento 2.

Also, please find [all contributors in the dedicated page](https://github.com/jacquesbh/jbh_router/graphs/contributors).

---
*On the road again*
