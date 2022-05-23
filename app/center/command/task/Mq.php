<?php
declare (strict_types = 1);

namespace app\center\command\task;

use app\common\library\mq\constant\MqTaskHandleType;
use app\common\library\mq\MqTaskHandle;
use think\console\Command;
use think\console\Input;
use think\console\input\Argument;
use think\console\input\Option;
use think\console\Output;

class Mq extends Command
{
    protected function configure()
    {
        // 指令配置
        $this->setName('task:mq')
            ->addArgument('mqTaskName', Argument::REQUIRED , "队列任务类名称")
            ->addArgument('p1', Argument::OPTIONAL, "参数1")
            ->addArgument('p2', Argument::OPTIONAL, "参数2")
            ->setDescription('the task:mq command of the mq task');
    }

    protected function execute(Input $input, Output $output)
    {
        //获取参数
        $mqTaskName = trim($input->getArgument('mqTaskName'));
        $p1 = $input->getArgument('p1') ? trim($input->getArgument('p1')) : '';
        $p2 = $input->getArgument('p2') ? trim($input->getArgument('p2')) : '';

        //选项参数

        //参数
        $params = [];
        $p1 && $params['p1'] = $p1;
        $p2 && $params['p2'] = $p2;

        //执行任务
        try{
            $mqTaskHandle = (new MqTaskHandle($mqTaskName,MqTaskHandleType::CONSUMER))->init();
            $className = "\\app\\center\\task\\mq\\{$mqTaskHandle->exchangeType}\\{$mqTaskName}";
            $mqTaskHandle->run((new $className($params)));
        }catch(\Throwable $e){
            $output->writeln($e->getMessage());
        }

        // 指令输出
        //$output->writeln($return);
        $output->writeln('完成');

    }
}
