# WDS BT v1.3.0-base - Base Theme Release

## Overview

This is a **base version** of WDS BT designed for private release branches in public repositories. This version serves as a stable foundation for projects that need to maintain version integrity while incorporating the latest improvements.

## Version Strategy

### **Base Version Purpose**
- **Private Release Branch**: Designed for internal/project-specific use
- **Version Integrity**: Maintains project-specific version numbers
- **Stable Foundation**: Provides a reliable base for customization
- **Patching Ready**: Optimized for selective feature adoption

### **Version Naming Convention**
- **Format**: `1.3.0-base`
- **Indicates**: Base version of the 1.3.0 release
- **Usage**: Private branches, project-specific deployments
- **Relationship**: Foundation for project-specific versions

## How to Use This Base Version

### **For New Projects**
```bash
# Clone the base version
git clone -b release/1.3.0-base https://github.com/WebDevStudios/wds-bt.git

# Set up your project
cd wds-bt
npm run setup
```

### **For Existing Projects**
```bash
# Create a project-specific branch from base
git checkout -b project/my-project-v1.3.0-base

# Apply your customizations
# ... your project-specific changes ...

# Update version to your project version
VERSION=my-project-v1.3.0 node updateVersion.js
```

## What's Included

### **Core Features**
- **Modern Block Theme Architecture**: WordPress 6.4+ compatibility
- **Enhanced Developer Experience**: Improved tooling and workflows
- **Accessibility Compliance**: WCAG 2.2 AA standards
- **Performance Optimizations**: Asset optimization and caching
- **Theme Options Panel**: Built-in performance controls

### **Development Tools**
- **Webpack 5 Configuration**: Optimized build system
- **Automated Quality Checks**: ESLint, Stylelint, PHPCS
- **Accessibility Testing**: Pa11y CI integration
- **Version Management**: Automated version updating
- **Block Creation Scripts**: Rapid block development

## Patching Strategy

### **Recommended Approach**
1. **Start with Base**: Use this version as your foundation
2. **Selective Patching**: Apply only needed features from future releases
3. **Version Lock**: Maintain your project's version number
4. **Documentation**: Track applied patches and customizations

### **What to Patch**
- **Security Updates**: Always apply security patches
- **Performance Improvements**: Build optimizations, asset management
- **Accessibility Fixes**: WCAG compliance improvements
- **Critical Bug Fixes**: Functionality fixes

### **What to Avoid**
- **Breaking Changes**: Major structural modifications
- **Unnecessary Features**: Features not relevant to your project
- **Version Dependencies**: Updates requiring WordPress version changes

## Getting Started

### **Requirements**
- WordPress 6.4+
- PHP 8.2+ (fully tested with PHP 8.3)
- Node.js 20+
- NPM 10+
- Composer 2+

### **Quick Setup**
```bash
# Install dependencies
npm run setup

# Start development
npm run start

# Build for production
npm run build
```

## Version Management

### **Using the Version Script**
```bash
# Update to your project version
VERSION=my-project-v1.3.0 node updateVersion.js

# Update to a patch version
VERSION=my-project-v1.3.1 node updateVersion.js
```

### **Files Updated**
- `package.json` - NPM package version
- `style.css` - WordPress theme version
- `README.md` - Documentation version
- `composer.json` - PHP package version

## Security & Maintenance

### **Security Features**
- **Input Sanitization**: Proper data handling
- **XSS Protection**: Secure output escaping
- **Security Headers**: Built-in security enhancements
- **Regular Updates**: Security patch application

### **Maintenance Best Practices**
- **Regular Backups**: Before applying patches
- **Testing Environment**: Test patches before production
- **Documentation**: Track all changes and customizations
- **Version Control**: Maintain clear version history

## Support

### **For Base Version Issues**
- **GitHub Issues**: Report problems with the base version
- **Documentation**: Check README.md for implementation guides


---

**Ready to build your project on a solid foundation?** This base version provides everything you need to create amazing WordPress experiences while maintaining version integrity and customization flexibility.

For more information, visit [WebDevStudios](https://webdevstudios.com/) or check the main [README.md](README.md).
