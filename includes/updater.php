<?php

class GitHubUpdater
{
  private $slug = '';
  private $plugin_file = '';
  private $repo = '';
  private $version;

  public function __construct($version, $slug, $plugin_file, $repo)
  {
    $this->version = $version;
    $this->slug = $slug;
    $this->plugin_file = $plugin_file;
    $this->repo = $repo;
    add_filter('pre_set_site_transient_update_plugins', [$this, 'check_update']);
    add_filter('plugins_api', [$this, 'get_plugin_info'], 20, 3);
  }

  public function get_remote()
  {
    $readme_file_url = 'https://raw.githubusercontent.com/' . $this->repo . '/refs/heads/main/readme.txt?time=' . time();
    return get_file_data($readme_file_url, array(
      'stable_tag' => 'Stable tag',
      'tested_up_to' => 'Tested up to',
      'requires_php' => 'Requires PHP',
      'requires_wp' => 'Requires at least',
    ));
  }

  public function get_plugin_info($result, $action, $args)
  {
    if ('plugin_information' !== $action || empty($args->slug) || $args->slug !== $this->slug) {
      return $result;
    }

    try {
      $data = $this->get_remote();
      if (!$data) {
        return $result;
      }
      if (!empty($data)) {
        return (object) [
          'name' => 'Flexsmith',
          'slug' => 'flexsmith',
          'version' => $data['stable_tag'],
          'requires' => $data['requires_wp'],
          'tested' => $data['tested_up_to'],
          'requires_php' => $data['requires_php'],
          'last_updated' => '',
          'download_link' => 'https://github.com/' . $this->repo . '/releases/download/v' . $data['stable_tag'] . '/' . $this->slug . '.zip',
          'sections' => [],
        ];
      }
      return [];
    } catch (\Throwable $th) {
      throw $th;
    }
  }

  public function check_update($transient)
  {

    $data = $this->get_remote();
    if ($data && version_compare($this->version, $data->version, '<')) {
      $transient->response[$this->plugin_file] = (object) [
        'slug' => $this->slug,
        'plugin' => $this->plugin_file,
        'new_version' => $data->version ?? '',
        'package' => $data->download_link ?? '',
        'tested' => $data->tested ?? '',
        'requires_php' => $data->requires_php ?? '',
        'url' => 'https://github.com/' . $this->repo,
      ];
    }
    return $transient;
  }
}

