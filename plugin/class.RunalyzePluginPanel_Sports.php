<?php
/**
 * This file contains the class of the RunalyzePluginPanel "Sports".
 * @package Runalyze\Plugins\Panels
 */
$PLUGINKEY = 'RunalyzePluginPanel_Sports';
/**
 * Class: RunalyzePluginPanel_Sports
 * @author Hannes Christiansen
 * @package Runalyze\Plugins\Panels
 */
class RunalyzePluginPanel_Sports extends PluginPanel {
	/**
	 * Initialize this plugin
	 * @see PluginPanel::initPlugin()
	 */
	protected function initPlugin() {
		$this->type = Plugin::$PANEL;
		$this->name = 'Sportarten';
		$this->description = '&Uuml;bersicht der Leistungen aller Sportarten f&uuml;r den aktuellen Monat, das Jahr oder seit Anfang der Aufzeichnung.';

		if (!$this->config['show_as_table']['var'])
			$this->removePanelContentPadding = true;
	}

	/**
	 * Set default config-variables
	 * @see PluginPanel::getDefaultConfigVars()
	 */
	protected function getDefaultConfigVars() {
		$config = array();
		$config['show_as_table'] = array('type' => 'bool', 'var' => false, 'description' => 'Alte Tabellenansicht');

		return $config;
	}

	/**
	 * Method for getting the right symbol(s)
	 * @see PluginPanel::getRightSymbol()
	 */
	protected function getRightSymbol() {
		$html = '<ul>';

		foreach ($this->getTimeset() as $i => $timeset)
			$html .= '<li>'.Ajax::change($timeset['name'], 'sports', '#sports_'.$i).'</li>';
	
		return $html.'</ul>';
	}

	/**
	 * Display the content
	 * @see PluginPanel::displayContent()
	 */
	protected function displayContent() {
		$Query = '
			SELECT
				`sportid`,
				COUNT(`id`) as `count`,
				SUM(`distance`) as `distance`,
				SUM(`s`) as `time_in_s`,
				SUM(`distance` > 0) as `count_distance`
			FROM `'.PREFIX.'training`
			WHERE
				`time` >=:start
			GROUP BY `sportid`
			ORDER BY `distance` DESC, `time_in_s` DESC';
		$Request = DB::getInstance()->prepare($Query);

		echo '<div id="sports">';
	
		foreach ($this->getTimeset() as $i => $timeset) {
			echo '<div id="sports_'.$i.'" class="change"'.($i==0 ? '' : ' style="display:none;"').'>';

			$Request->bindValue('start', $timeset['start'], PDO::PARAM_INT);
			$Request->execute();
			$data = $Request->fetchAll();

			foreach ($data as $dat) {
				// TODO: Define the decision (distance or time) somehow in the configuration
				$Sport = new Sport($dat['sportid']);

				if ($this->config['show_as_table']['var']) {
					$result = $dat['count_distance'] >= $dat['count']/2
						? Helper::Unknown(Running::Km($dat['distance']), '0,0 km')
						: Time::toString($dat['time_in_s']);

					echo '<p><span class="right"><small><small>('.Helper::Unknown($dat['count'], '0').'-mal)</small></small> '.$result.'</span> ';
					echo $Sport->Icon().' <strong>'.$Sport->name().'</strong></p>';
				} else {
					$Value = new BoxedValue();
					$Value->setIcon($Sport->Icon());
					$Value->setInfo($Sport->name());
					$Value->defineAsFloatingBlock('w50');

					if ($dat['count_distance'] >= $dat['count']/2) {
						$Value->setValue( Running::KmFormat($dat['distance']) );
						$Value->setUnit('km');
					} else {
						$Value->setValue(Time::toString($dat['time_in_s'], false, true));
					}

					$Value->display();
				}
			}

			if (empty($data))
				echo '<p><em>Noch keine Daten vorhanden.</em></p>';
	
			if ($this->config['show_as_table']['var']) {
				echo '<small class="right">seit '.date("d.m.Y", $timeset['start']).'</small>';
				echo HTML::clearBreak();
			}

			echo '</div>';
		}
	
		echo '</div>';
	}

	/**
	 * Get the timeset as array for this panel
	 */
	private function getTimeset() {
		$timeset = array(
			array(
				'name'	=> 'Monat',
				'start'	=> mktime(0,0,0,date("m"),1,date("Y"))
			),
			array(
				'name'	=> 'Jahr',
				'start'	=> mktime(0,0,0,1,1,date("Y"))
			),
			array(
				'name'	=> 'Gesamt',
				'start'	=> START_TIME
			)
		);
	
		return $timeset;
	}
}