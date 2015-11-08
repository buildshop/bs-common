<?php
/**
 * Валидатор специально для изображений если сохранять пустое поле.
 */
class FileValidator extends CFileValidator {

    protected function emptyAttribute($object, $attribute) {

    }

}