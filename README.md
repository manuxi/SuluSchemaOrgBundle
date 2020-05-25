#Sulu SchemaOrg Bundle

This bundle integrate microformats from Schema.org to your project.

## Instalation

```shell script
composer requiere the-cocktail/sulu-schema-org-bundle
```

Insert this code into your "base.html.twig" so that the entire generated schema is automatically inserted into the response.
```
  {{ constant('TheCocktail\\Bundle\\SuluSchemaOrgBundle\\Factory\\SchemaOrgFactory::TWIG_KEY')|raw }}
```

Now you can define Schema types for each template adding a 'sulu.schema_org' tag for each template and elements.

Basic example in homepage.xml

```xml
    <key>homepage</key>
    <tag name="sulu.schema_org" itemtype="WebSite"/>

    <view>pages/homepage</view>
    <controller>Sulu\Bundle\WebsiteBundle\Controller\DefaultController::indexAction</controller>
    <cacheLifetime>86400</cacheLifetime>

    <meta>
        <title lang="en">Homepage</title>
        <title lang="es">Homepage</title>
    </meta>

    <properties>
        <property name="title" type="text_line" mandatory="true">
            <meta>
                <title lang="en">Title</title>
                <title lang="es">Título</title>
            </meta>
            <params>
                <param name="headline" value="true"/>
            </params>
            <tag name="sulu.schema_org" itemtype="WebSite" itemprop="name"/>
            <tag name="sulu.rlp.part"/>
        </property>

        <property name="url" type="resource_locator" mandatory="true">
            <meta>
                <title lang="es">Slug</title>
                <title lang="en">Slug</title>
            </meta>
            <tag name="sulu.schema_org" itemtype="WebSite" itemprop="url"/>
            <tag name="sulu.rlp"/>
        </property>
    </properties>
</template>
```

# Configuration

There are some useful options for self-assigning Seo and Extracts properties

```yaml
sulu_schema_org:
    # image format generated for all image types
    image_format: "sulu-240x"

    organization:
        enabled: true
        schema: EducationalOrganization
        uid: main

    extensions:
        seo:
            default:
                title:
                    property: name
                    type: text_line
                description:
                    property: description
                    type: text_line
            Article:
                description:
                    property: headline
                    type: text_line
        excerpt:
            default:
                icon:
                    property: image
                    type: media_selection
```


## Tags in blocks 

You can define properties in all blocks of the project by assigning the fields according to the scope.

```xml
    <type name="hero">
        <meta>
            <title lang="es">01M - Hero</title>
            <title lang="en">01M - Hero</title>
        </meta>
        <properties>
            <property name="title" type="text_line" mandatory="true">
                <meta>
                    <title lang="es">Título</title>
                    <title lang="en">Title</title>
                </meta>
            </property>
            <property name="images" type="media_selection" mandatory="true" maxOccurs="1">
                <meta>
                    <title lang="es">Imágenes</title>
                    <title lang="en">Images</title>
                </meta>
                <tag name="sulu.schema_org" itemtype="WebSite" itemprop="image"/>
            </property>
        </properties>
    </type>
```

Sometimes the field is used in multiple scopes, then you can change 'itemtype' property by "*"
```shell script
   <tag name="sulu.schema_org" itemtype="*" itemprop="image"/>
```

## Complex structures

Many schemas define child objects, like Addrees in a Event, so you must register all in template with its scope.
The property *itemscope* defines the parent so you must specify
```
    <tag name="sulu.schema_org" itemtype="Event"/>
    <tag name="sulu.schema_org" itemtype="Place" itemscope="Event" itemprop="location" />
    <tag name="sulu.schema_org" itemtype="PostalAddress" itemscope="Place" itemprop="address" />
```


Now if a property with *itemtype="PostalAddress* exists in your code it will be assigned to its schema:
```shell script
  <property name="city" type="text_line" mandatory="true">
    <meta>
      <title lang="en">City</title>
    </meta>
    <tag name="sulu.schema_org" itemtype="PostalAddress" itemscope="Place" itemprop="addressLocality" />
  </property> 
  <property name="zip" type="text_line" mandatory="true">
    <meta>
      <title lang="en">Zip</title>
    </meta>
    <tag name="sulu.schema_org" itemtype="PostalAddress" itemscope="Place" itemprop="postalCode" />
  </property>
 
  ...
```

## Breadcrumb 

There is a Twig Function to generate Breadcrumb schema. Put in any place of your twig templates:

```shell script
  {{ sulu_schema_org('breadcrumb', breadcrumb) }}
```

## Organizations

If organitation option is enabled in configuration. An Organization Schema with *uid* defined will be inyected in schema list.
You must specify the type of your organization. [See some types here](https://schema.org/Organization#subtypes)
