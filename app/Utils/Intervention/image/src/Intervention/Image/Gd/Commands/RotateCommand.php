<?php

namespace App\Utils\Intervention\Image\Gd\Commands;

use App\Utils\Intervention\Image\Commands\AbstractCommand;
use App\Utils\Intervention\Image\Gd\Color;

class RotateCommand extends AbstractCommand
{
    /**
     * Rotates image counter clockwise
     *
     * @param  \Intervention\Image\Image $image
     * @return boolean
     */
    public function execute($image)
    {
        $angle = $this->argument(0)->type('numeric')->required()->value();
        $color = $this->argument(1)->value();
        $color = new Color($color);

        // rotate image
        $image->setCore(imagerotate($image->getCore(), $angle, $color->getInt()));

        return true;
    }
}
