<?php

declare(strict_types=1);

/*
 * @author    https://github.com/Xirdion
 * @link      https://github.com/Xirdion/simple-media-library
 */

namespace App\Video;

use App\Model\VideoModel;
use SimpleXMLElement;

class XmlGenerator
{
    private VideoModel $videoModel;

    /**
     * @param VideoModel $videoModel
     *
     * @return string
     */
    public function generate(VideoModel $videoModel): string
    {
        $this->videoModel = $videoModel;

        // Minimum xml data to start the creation
        $xmlString = <<<'XML'
            <?xml version="1.0" encoding="utf-8" ?>
                <xs:schema xmlns:xs="http://www.w3.org/2001/XMLSchema">
            </xs:schema>
            XML;

        try {
            $xml = new SimpleXMLElement($xmlString);
        } catch (\Exception $e) {
            $xml = null;
        }
        if (null === $xml) {
            return '';
        }

        // Add the elements that are independent of the video data
        $start = $this->getXmlStartElement($xml);

        // Add the specific video data
        $this->addPropertiesToXml($start);

        // Format the xml correctly
        $result = $xml->asXML();
        $search = ['><', '<xs:complexType', '</xs:complexType', '<xs:sequence>', '</xs:sequence>', '<xs:element'];
        $replace = ['>' . \PHP_EOL . '<', '  <xs:complexType', '  </xs:complexType', '  <xs:sequence>', '  </xs:sequence>', '    <xs:element'];

        return str_replace($search, $replace, $result);
    }

    /**
     * @param SimpleXMLElement $xml
     *
     * @return SimpleXMLElement|null
     */
    private function getXmlStartElement(SimpleXMLElement $xml): ?SimpleXMLElement
    {
        $video = $xml->addChild('complexType');
        $video?->addAttribute('name', 'video');

        return $video?->addChild('sequence');
    }

    /**
     * @param SimpleXMLElement|null $xml
     *
     * @return void
     */
    private function addPropertiesToXml(?SimpleXMLElement $xml): void
    {
        $reflection = new \ReflectionClass($this->videoModel);
        $props = $reflection->getProperties(\ReflectionProperty::IS_PRIVATE);
        foreach ($props as $prop) {
            $getter = 'get' . implode('', array_map(static fn (string $fieldPart) => ucfirst($fieldPart), explode('_', $prop->getName())));
            $value = $this->videoModel->{$getter}();
            $element = $xml?->addChild('element', (string) $value);
            $element?->addAttribute('name', $prop->getName());
            $element?->addAttribute('type', $this->getXmlType($prop->getType()?->getName()));
        }
    }

    /**
     * @param string $propType
     *
     * @return string
     */
    private function getXmlType(string $propType): string
    {
        return match ($propType) {
            'int' => 'xs:integer',
            \DateTimeInterface::class => 'xs:datetime',
            default => 'xs:string',
        };
    }
}
