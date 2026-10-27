import { useSelect } from '@wordpress/data';
export function useDeviceType() {
  return useSelect((select) => {
    // WP 6.5+: post editor AND site editor
    const editor = select('core/editor');
    if (editor?.getDeviceType) {
      return editor.getDeviceType();
    }

    // Older site editor
    const editSite = select('core/edit-site');
    if (editSite?.__experimentalGetPreviewDeviceType) {
      return editSite.__experimentalGetPreviewDeviceType();
    }

    // Older post editor
    const editPost = select('core/edit-post');
    if (editPost?.__experimentalGetPreviewDeviceType) {
      return editPost.__experimentalGetPreviewDeviceType();
    }

    return 'Desktop';
  }, []);
}

import {
  AlignVerticalJustifyEnd,
  AlignVerticalJustifyStart,
  AlignVerticalJustifyCenter,
  AlignVerticalDistributeCenter,
  AlignVerticalSpaceBetween,
  StretchVertical,
  AlignStartVertical,
  AlignEndVertical,
  AlignHorizontalJustifyCenter,
  AlignHorizontalSpaceBetween,
  AlignHorizontalSpaceAround,
  StretchHorizontal,
  ArrowRight,
  ArrowDown,
  ArrowUp,
  ArrowLeft,
  Ban,
} from "lucide-react";


export const ICONS = {
  VJustifyEnd: AlignVerticalJustifyEnd,
  VJustifyStart: AlignVerticalJustifyStart,
  VJustifyCenter: AlignVerticalJustifyCenter,
  VDistributeCenter: AlignVerticalDistributeCenter,
  VSpaceBetween: AlignVerticalSpaceBetween,
  VStrech: StretchVertical,
  HStart: AlignStartVertical,
  HEnd: AlignEndVertical,
  HJustifyCenter: AlignHorizontalJustifyCenter,
  HSpaceBetween: AlignHorizontalSpaceBetween,
  HSpaceAround: AlignHorizontalSpaceAround,
  HStrech: StretchHorizontal,
  Ban: Ban,
  ArrowUp: ArrowUp,
  ArrowRight: ArrowRight,
  ArrowDown: ArrowDown,
  ArrowLeft: ArrowLeft,
}

export const getWidth = (deviceType, attributes) => {
  if (deviceType == "Desktop") {
    return attributes.width ? attributes.width : undefined
  } else if (deviceType == "Tablet") {
    return attributes.tabletWidth ? attributes.tabletWidth : undefined
  } else {
    return attributes.mobileWidth ? attributes.mobileWidth : undefined
  }
}

export const getColumnGap = (deviceType, attributes) => {
  if (deviceType == "Desktop") {
    return attributes.columnGap ? attributes.columnGap : undefined
  } else if (deviceType == "Tablet") {
    return attributes.tabletColumnGap ? attributes.tabletColumnGap : undefined
  } else {
    return attributes.mobileColumnGap ? attributes.mobileColumnGap : undefined
  }
}

export const getRowGap = (deviceType, attributes) => {
  if (deviceType == "Desktop") {
    return attributes.rowGap ? attributes.rowGap : undefined
  } else if (deviceType == "Tablet") {
    return attributes.tabletRowGap ? attributes.tabletRowGap : undefined
  } else {
    return attributes.mobileRowGap ? attributes.mobileRowGap : undefined
  }
}

const CLASS_PROPS = [
  ['justify', 'justifyContent'],
  ['align', 'alignItems'],
  ['direction', 'direction'],
  ['wrap', 'wrap'],
  ['display', 'display'],
  ['grow', 'grow'],
  ['shrink', 'shrink'],
];

const DEVICES = ['', 'tablet', 'mobile'];

export const classNames = (attributes) => {
  const classes = ['taufb'];

  for (const [slug, attr] of CLASS_PROPS) {
    const cap = attr.charAt(0).toUpperCase() + attr.slice(1);

    for (const device of DEVICES) {
      const value = attributes[device ? `${device}${cap}` : attr];
      if (value) {
        classes.push(`taufb--${slug}-${device ? `${device}-` : ''}${value}`);
      }
    }
  }

  return classes;
};