<?php

namespace Cloudtel\ReportGenerator\Facades;

use Illuminate\Support\Facades\Facade as IlluminateFacade;

/**
 * @see \Cloudtel\ReportGenerator\ReportMedia\PdfReport
 */
class PdfReportFacade extends IlluminateFacade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'pdf.report.generator';
    }
}
