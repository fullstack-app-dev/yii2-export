<?php declare(strict_types=1);

namespace hiqdev\yii2\export\actions;

use hipanel\actions\IndexAction;
use hiqdev\yii2\export\models\enums\ExportStatus;
use hiqdev\yii2\export\models\ExportJob;
use Yii;

class DownloadExportAction extends IndexAction
{
    public function run()
    {
        $id = $this->controller->request->get('id', '');
        $job = ExportJob::findOrNew($id);
        if ($job->status === ExportStatus::SUCCESS->value) {
            $stream = $job->getSaver()->getStream();
            $filename = $job->getSaver()->getFilename();
            $job->delete();

            return $this->controller->response->sendStreamAsFile($stream, $filename);
        }
        Yii::$app->end();
    }
}
