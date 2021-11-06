<?php

declare(strict_types=1);

namespace Bimarestan;


use pocketmine\Server;
use pocketmine\Player;

use pocketmine\plugin\Plugin;
use pocketmine\plugin\PluginBase;

use pocketmine\event\Listener;
use pocketmine\utils\TextFormat as C;

use pocketmine\math\Vector3;
use pocketmine\level\Position;

use pocketmine\level\Level;

use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\command\CommandExecutor;
use pocketmine\command\ConsoleCommandSender;

use pocketmine\utils\Config;
use jojoe77777\FormAPI;
use jojoe77777\FormAPI\SimpleForm;
use jojoe77777\FormAPI\CustomForm;

use onebone\ecomonyapi\EconomyAPI;

class Main extends PluginBase implements Listener
{

	public function onEnable()
	{
		$this->getLogger()->info("plugin HealMenu Enable Shod!");
		$this->getServer()->getPluginManager()->registerEvents($this, $this);
		$this->eco = $this->getServer()->getPluginManager()->getPlugin("EconomyAPI");

		@mkdir($this->getDataFolder());
		$this->saveDefaultConfig();
		$this->getResource("config.yml");
	}



	public function onCommand(CommandSender $sender, Command $cmd, string $label, array $args): bool
	{
		switch ($cmd->getName()) {
			case "healmenu":
				if ($sender instanceof Player) {
					$this->openMyForm($sender);
				} else {
					$sender->sendMessage("In Command Ro Dar Game Type Konid .");
				}
		}
		return true;
	}

	public function openMyForm($sender)
	{
		$form = $this->getServer()->getPluginManager()->getPlugin("FormAPI")->createSimpleForm(function (Player $player, int $data = null){
			if($data === null){
				return true;
			}
			switch ($data) {
				case 0:
					$this->Health($player);
					break;

				case 1:
					$this->Health50($player);
					break;

				case 2:
					$player->sendMessage($this->getConfig()->get("cancelled"));
					break;
			}
		});
		$form->setTitle("§6» §eHealMenu §6«§r");
		$form->setContent("Entekhab Kardn Heal");
		$form->addButton("§a§lHeal\n§r§8Baraye Kharid Click Konid", 0, "textures/ui/heart_new");
		$form->addButton("§a§lHeal 50%\n§r§8Baraye Kharid Click Konid", 0, "textures/ui/heart_new");
		$form->addButton("§l§cKhrooj\n§r§8Baraye Khrooj Click Konid", 0, "textures/blocks/barrier");
		$form->sendToPlayer($sender);
		return $form;

	}

	public function Health($sender)
	{
		$form = $this->getServer()->getPluginManager()->getPlugin("FormAPI")->createSimpleForm(function (Player $player, int $data = null){
			if($data === null){
				return true;
			}
			switch ($data) {
				case 1:
					$money = $this->eco->myMoney($player);
					$heal = $this->getConfig()->get("heal.cost");
					if ($money >= $heal) {

						$this->eco->reduceMoney($player, $heal);
						$player->setHealth(20);
						$player->sendMessage($this->getConfig()->get("heal.success"));
						return true;
					} else {
						$player->sendMessage($this->getConfig()->get("heal.no.money"));
					}
					break;
				case 2:
					$player->sendMessage($this->getConfig()->get("heal.cancelled"));
					break;
			}
			return true;
		});

		$form->setTitle("§6» §eHealMenu §6«§r");
		$form->setContent($this->getConfig()->get("heal.content"));
		$form->setButton1("Taeid", 1);
		$form->setButton2("Khrooj", 2);
		$form->sendToPlayer($sender);
		return $form;

	}

public function Health50($sender)
{
	$form = $this->getServer()->getPluginManager()->getPlugin("FormAPI")->createSimpleForm(function (Player $player, int $data = null){
		if($data === null){
			return true;
		}
		switch ($data) {
			case 1:
				$money = $this->eco->myMoney($player);
				$heal = $this->getConfig()->get("heal.cost");
				if ($money >= $heal) {

					$this->eco->reduceMoney($player, $heal);
					$player->setHealth(10);
					$player->sendMessage($this->getConfig()->get("heal.success"));
					return true;
				} else {
					$player->sendMessage($this->getConfig()->get("heal.no.money"));
				}
				break;
			case 2:
				$player->sendMessage($this->getConfig()->get("heal.cancelled"));
				break;
		}
		return true;
	});

	$form->setTitle("§6» §eHealMenu §6«§r");
	$form->setContent($this->getConfig()->get("heal.content"));
	$form->setButton1("Taeid", 1);
	$form->setButton2("Khrooj", 2);
	$form->sendToPlayer($sender);
	return $form;

}


}





