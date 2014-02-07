<?php
/**
 * Created by PhpStorm.
 * User: bmatt
 * Date: 29/01/2014
 * Time: 18:18
 */


return array(
    'main' => Menu::handler('categories')
            ->add('algorithms', 'Algorithms', Menu::items()->prefixParents()
                ->add('cryptography', 'Cryptography')
                ->add('data-structures', 'Data Structures')
                ->add('digital-image-processing', 'Digital Image Processing')
                ->add('memory-management', 'Memory Management'))
            ->add('graphics-and-multimedia', 'Graphics & Multimedia', Menu::items()->prefixParents()
                ->add('directx', 'DirectX')
                ->add('flash', 'Flash')
                ->add('opengl', 'OpenGL'))
);