<?php

namespace PhpOffice\PhpSpreadsheet\RichText;

use PhpOffice\PhpSpreadsheet\Cell\Cell;
use PhpOffice\PhpSpreadsheet\Cell\DataType;
use PhpOffice\PhpSpreadsheet\IComparable;

class RichText implements IComparable
{
    /**
     * Rich text elements.
     *
     * @var ITextElement[]
     */
    private $richTextElements;

    /**
     * Create a new RichText instance.
     */
    public function __construct(?Cell $cell = null)
    {
        // Initialise variables
        $this->richTextElements = [];

        // Rich-Text string attached to cell?
        if ($cell !== null) {
            // Add cell text and style
            if ($cell->getValue() != '') {
                $objRun = new Run($cell->getValue());
                $objRun->setFont(clone $cell->getWorksheet()->getStyle($cell->getCoordinate())->getFont());
                $this->addText($objRun);
            }

            // Set parent value
            $cell->setValueExplicit($this, DataType::TYPE_STRING);
        }
    }

    /**
     * Add text.
     *
     * @param ITextElement $text Rich text element
     *
     * @return $this
     */
    public function addText(ITextElement $text)
    {
        $this->richTextElements[] = $text;

        return $this;
    }

    /**
     * Create text.
     *
     * @param string $text Text
     *
     * @return TextElement
     */
    public function createText($text)
    {
        $objText = new TextElement($text);
        $this->addText($objText);

        return $objText;
    }

    /**
     * Create text run.
     *
     * @param string $text Text
     *
     * @return Run
     */
    public function createTextRun($text)
    {
        $objText = new Run($text);
        $this->addText($objText);

        return $objText;
    }

    /**
     * Get plain text.
     *
     * @return string
     */
    public function getPlainText()
    {
        // Return value
        $returnValue = '';

        // Loop through all ITextElements
        foreach ($this->richTextElements as $text) {
            $returnValue .= $text->getText();
        }

        return $returnValue;
    }

    /**
     * Convert to string.
     *
     * @return string
     */
    public function __toString()
    {
        return $this->getPlainText();
    }

    /**
     * Get Rich Text elements.
     *
     * @return ITextElement[]
     */
    public function getRichTextElements()
    {
        return $this->richTextElements;
    }

    /**
     * Set Rich Text elements.
     *
     * @param ITextElement[] $textElements Array of elements
     *
     * @return $this
     */
    public function setRichTextElements(array $textElements)
    {
        $this->richTextElements = $textElements;

        return $this;
    }

    public function getArrayElements(){
        // Return value
        $array = [];
        $conteo = 0;

        // Recorrer cada elemento
        foreach ($this->richTextElements as $text => $value) {
            // Obtener el texto y limpiarlo con trim
            $array[$conteo] = trim($value->getText());
            $conteo++;
        }

        return $array;
    }

    /**
     * Get hash code.
     *
     * @return string Hash code
     */
    public function getHashCode()
    {
        $hashElements = '';
        foreach ($this->richTextElements as $element) {
            $hashElements .= $element->getHashCode();
        }

        return md5(
            $hashElements .
            __CLASS__
        );
    }

    /**
     * Implement PHP __clone to create a deep clone, not just a shallow copy.
     */
    public function __clone()
    {
        $vars = get_object_vars($this);
        foreach ($vars as $key => $value) {
            if (is_object($value)) {
                $this->$key = clone $value;
            } else {
                $this->$key = $value;
            }
        }
    }
}
