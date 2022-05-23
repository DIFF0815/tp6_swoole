<?php
declare (strict_types = 1);

namespace app\center\command\task;

use think\console\Command;
use think\console\Input;
use think\console\input\Argument;
use think\console\input\Option;
use think\console\Output;

class Cron extends Command
{
    protected function configure()
    {
        // 指令配置
        $this->setName('task:cron')
            ->addArgument('cronTaskName', Argument::REQUIRED , "计划任务类名称")
            ->addArgument('p1', Argument::OPTIONAL, "参数1")
            ->addArgument('p2', Argument::OPTIONAL, "参数2")
            ->addArgument('p3', Argument::OPTIONAL, "参数3")
            ->addArgument('p4', Argument::OPTIONAL, "参数4")
            ->addArgument('p5', Argument::OPTIONAL, "参数5")
            ->addArgument('p6', Argument::OPTIONAL, "参数6")
            ->setDescription('the task:cron command of the plan task');
    }

    protected function execute(Input $input, Output $output)
    {
        //获取参数
        $cronTaskName = trim($input->getArgument('cronTaskName'));
        $p1 = $input->getArgument('p1') ? trim($input->getArgument('p1')) : '';
        $p2 = $input->getArgument('p2') ? trim($input->getArgument('p2')) : '';
        $p3 = $input->getArgument('p3') ? trim($input->getArgument('p3')) : '';
        $p4 = $input->getArgument('p4') ? trim($input->getArgument('p4')) : '';
        $p5 = $input->getArgument('p5') ? trim($input->getArgument('p5')) : '';
        $p6 = $input->getArgument('p6') ? trim($input->getArgument('p6')) : '';

        //选项参数

        //参数
        $params = [];
        $p1 && $params['p1'] = $p1;
        $p2 && $params['p2'] = $p2;
        $p3 && $params['p3'] = $p3;
        $p4 && $params['p4'] = $p4;
        $p5 && $params['p5'] = $p5;
        $p6 && $params['p6'] = $p6;

        //执行任务
        try {
            $cronTaskClassName = "\\app\\center\\task\\cron\\{$cronTaskName}";
            $return = (new $cronTaskClassName)->run($params);
        }catch(\Throwable $e){
            $return = $e->getMessage();
        }

        // 指令输出
        $output->writeln($return);
        $output->writeln('完成');

    }
}
