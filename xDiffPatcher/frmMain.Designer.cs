namespace xDiffPatcher
{
    partial class frmMain
    {
        /// <summary>
        /// Required designer variable.
        /// </summary>
        private System.ComponentModel.IContainer components = null;

        /// <summary>
        /// Clean up any resources being used.
        /// </summary>
        /// <param name="disposing">true if managed resources should be disposed; otherwise, false.</param>
        protected override void Dispose(bool disposing)
        {
            if (disposing && (components != null))
            {
                components.Dispose();
            }
            base.Dispose(disposing);
        }

        #region Windows Form Designer generated code

        /// <summary>
        /// Required method for Designer support - do not modify
        /// the contents of this method with the code editor.
        /// </summary>
        private void InitializeComponent()
        {
            this.components = new System.ComponentModel.Container();
            System.ComponentModel.ComponentResourceManager resources = new System.ComponentModel.ComponentResourceManager(typeof(frmMain));
            this.groupBox1 = new System.Windows.Forms.GroupBox();
            this.btnLoad = new System.Windows.Forms.Button();
            this.btnOpenDiff = new System.Windows.Forms.Button();
            this.txtDiffFile = new System.Windows.Forms.TextBox();
            this.btnOpenExe = new System.Windows.Forms.Button();
            this.label2 = new System.Windows.Forms.Label();
            this.txtExeFile = new System.Windows.Forms.TextBox();
            this.label1 = new System.Windows.Forms.Label();
            this.grpDiff = new System.Windows.Forms.GroupBox();
            this.lblModifierInfo = new System.Windows.Forms.Label();
            this.btnSaveProfile = new System.Windows.Forms.Button();
            this.picModifier = new System.Windows.Forms.PictureBox();
            this.txtModifier = new System.Windows.Forms.TextBox();
            this.cmbModifiers = new System.Windows.Forms.ComboBox();
            this.lblModifiers = new System.Windows.Forms.Label();
            this.txtDesc = new System.Windows.Forms.TextBox();
            this.label3 = new System.Windows.Forms.Label();
            this.lstPatches = new xDiffPatcher.PatchList();
            this.btnApplyProfile = new System.Windows.Forms.Button();
            this.btnApplyLast = new System.Windows.Forms.Button();
            this.btnSave = new System.Windows.Forms.Button();
            this.mnuStrip = new System.Windows.Forms.MenuStrip();
            this.mnuProfiles = new System.Windows.Forms.ToolStripMenuItem();
            this.imgListModifier = new System.Windows.Forms.ImageList(this.components);
            this.aboutToolStripMenuItem = new System.Windows.Forms.ToolStripMenuItem();
            this.groupBox1.SuspendLayout();
            this.grpDiff.SuspendLayout();
            ((System.ComponentModel.ISupportInitialize)(this.picModifier)).BeginInit();
            this.mnuStrip.SuspendLayout();
            this.SuspendLayout();
            // 
            // groupBox1
            // 
            this.groupBox1.Anchor = ((System.Windows.Forms.AnchorStyles)(((System.Windows.Forms.AnchorStyles.Top | System.Windows.Forms.AnchorStyles.Left)
                        | System.Windows.Forms.AnchorStyles.Right)));
            this.groupBox1.Controls.Add(this.btnLoad);
            this.groupBox1.Controls.Add(this.btnOpenDiff);
            this.groupBox1.Controls.Add(this.txtDiffFile);
            this.groupBox1.Controls.Add(this.btnOpenExe);
            this.groupBox1.Controls.Add(this.label2);
            this.groupBox1.Controls.Add(this.txtExeFile);
            this.groupBox1.Controls.Add(this.label1);
            this.groupBox1.Location = new System.Drawing.Point(16, 39);
            this.groupBox1.Margin = new System.Windows.Forms.Padding(4);
            this.groupBox1.Name = "groupBox1";
            this.groupBox1.Padding = new System.Windows.Forms.Padding(4);
            this.groupBox1.Size = new System.Drawing.Size(524, 134);
            this.groupBox1.TabIndex = 0;
            this.groupBox1.TabStop = false;
            this.groupBox1.Text = "Input files";
            // 
            // btnLoad
            // 
            this.btnLoad.Anchor = ((System.Windows.Forms.AnchorStyles)((System.Windows.Forms.AnchorStyles.Top | System.Windows.Forms.AnchorStyles.Right)));
            this.btnLoad.Location = new System.Drawing.Point(384, 94);
            this.btnLoad.Margin = new System.Windows.Forms.Padding(4);
            this.btnLoad.Name = "btnLoad";
            this.btnLoad.Size = new System.Drawing.Size(132, 28);
            this.btnLoad.TabIndex = 6;
            this.btnLoad.Text = "Load!";
            this.btnLoad.UseVisualStyleBackColor = true;
            this.btnLoad.Click += new System.EventHandler(this.btnLoad_Click);
            // 
            // btnOpenDiff
            // 
            this.btnOpenDiff.Anchor = ((System.Windows.Forms.AnchorStyles)((System.Windows.Forms.AnchorStyles.Top | System.Windows.Forms.AnchorStyles.Right)));
            this.btnOpenDiff.Location = new System.Drawing.Point(469, 58);
            this.btnOpenDiff.Margin = new System.Windows.Forms.Padding(4);
            this.btnOpenDiff.Name = "btnOpenDiff";
            this.btnOpenDiff.Size = new System.Drawing.Size(47, 28);
            this.btnOpenDiff.TabIndex = 5;
            this.btnOpenDiff.Text = "...";
            this.btnOpenDiff.UseVisualStyleBackColor = true;
            this.btnOpenDiff.Click += new System.EventHandler(this.btnOpenDiff_Click);
            // 
            // txtDiffFile
            // 
            this.txtDiffFile.Anchor = ((System.Windows.Forms.AnchorStyles)(((System.Windows.Forms.AnchorStyles.Top | System.Windows.Forms.AnchorStyles.Left)
                        | System.Windows.Forms.AnchorStyles.Right)));
            this.txtDiffFile.Location = new System.Drawing.Point(91, 60);
            this.txtDiffFile.Margin = new System.Windows.Forms.Padding(4);
            this.txtDiffFile.Name = "txtDiffFile";
            this.txtDiffFile.Size = new System.Drawing.Size(369, 22);
            this.txtDiffFile.TabIndex = 4;
            // 
            // btnOpenExe
            // 
            this.btnOpenExe.Anchor = ((System.Windows.Forms.AnchorStyles)((System.Windows.Forms.AnchorStyles.Top | System.Windows.Forms.AnchorStyles.Right)));
            this.btnOpenExe.Location = new System.Drawing.Point(469, 23);
            this.btnOpenExe.Margin = new System.Windows.Forms.Padding(4);
            this.btnOpenExe.Name = "btnOpenExe";
            this.btnOpenExe.Size = new System.Drawing.Size(47, 28);
            this.btnOpenExe.TabIndex = 3;
            this.btnOpenExe.Text = "...";
            this.btnOpenExe.UseVisualStyleBackColor = true;
            this.btnOpenExe.Click += new System.EventHandler(this.btnOpenExe_Click);
            // 
            // label2
            // 
            this.label2.AutoSize = true;
            this.label2.Location = new System.Drawing.Point(8, 64);
            this.label2.Margin = new System.Windows.Forms.Padding(4, 0, 4, 0);
            this.label2.Name = "label2";
            this.label2.Size = new System.Drawing.Size(65, 17);
            this.label2.TabIndex = 2;
            this.label2.Text = "xDiff File:";
            // 
            // txtExeFile
            // 
            this.txtExeFile.Anchor = ((System.Windows.Forms.AnchorStyles)(((System.Windows.Forms.AnchorStyles.Top | System.Windows.Forms.AnchorStyles.Left)
                        | System.Windows.Forms.AnchorStyles.Right)));
            this.txtExeFile.Location = new System.Drawing.Point(91, 26);
            this.txtExeFile.Margin = new System.Windows.Forms.Padding(4);
            this.txtExeFile.Name = "txtExeFile";
            this.txtExeFile.Size = new System.Drawing.Size(369, 22);
            this.txtExeFile.TabIndex = 1;
            // 
            // label1
            // 
            this.label1.AutoSize = true;
            this.label1.Location = new System.Drawing.Point(8, 30);
            this.label1.Margin = new System.Windows.Forms.Padding(4, 0, 4, 0);
            this.label1.Name = "label1";
            this.label1.Size = new System.Drawing.Size(65, 17);
            this.label1.TabIndex = 0;
            this.label1.Text = "EXE File:";
            // 
            // grpDiff
            // 
            this.grpDiff.Anchor = ((System.Windows.Forms.AnchorStyles)((((System.Windows.Forms.AnchorStyles.Top | System.Windows.Forms.AnchorStyles.Bottom)
                        | System.Windows.Forms.AnchorStyles.Left)
                        | System.Windows.Forms.AnchorStyles.Right)));
            this.grpDiff.Controls.Add(this.lblModifierInfo);
            this.grpDiff.Controls.Add(this.btnSaveProfile);
            this.grpDiff.Controls.Add(this.picModifier);
            this.grpDiff.Controls.Add(this.txtModifier);
            this.grpDiff.Controls.Add(this.cmbModifiers);
            this.grpDiff.Controls.Add(this.lblModifiers);
            this.grpDiff.Controls.Add(this.txtDesc);
            this.grpDiff.Controls.Add(this.label3);
            this.grpDiff.Controls.Add(this.lstPatches);
            this.grpDiff.Controls.Add(this.btnApplyProfile);
            this.grpDiff.Controls.Add(this.btnApplyLast);
            this.grpDiff.Controls.Add(this.btnSave);
            this.grpDiff.Enabled = false;
            this.grpDiff.Location = new System.Drawing.Point(16, 181);
            this.grpDiff.Margin = new System.Windows.Forms.Padding(4);
            this.grpDiff.Name = "grpDiff";
            this.grpDiff.Padding = new System.Windows.Forms.Padding(4);
            this.grpDiff.Size = new System.Drawing.Size(524, 486);
            this.grpDiff.TabIndex = 1;
            this.grpDiff.TabStop = false;
            this.grpDiff.Text = "Diffydiff";
            // 
            // lblModifierInfo
            // 
            this.lblModifierInfo.Anchor = ((System.Windows.Forms.AnchorStyles)((System.Windows.Forms.AnchorStyles.Bottom | System.Windows.Forms.AnchorStyles.Right)));
            this.lblModifierInfo.AutoSize = true;
            this.lblModifierInfo.Location = new System.Drawing.Point(223, 323);
            this.lblModifierInfo.Name = "lblModifierInfo";
            this.lblModifierInfo.Size = new System.Drawing.Size(0, 17);
            this.lblModifierInfo.TabIndex = 14;
            // 
            // btnSaveProfile
            // 
            this.btnSaveProfile.Anchor = System.Windows.Forms.AnchorStyles.Bottom;
            this.btnSaveProfile.Location = new System.Drawing.Point(186, 405);
            this.btnSaveProfile.Margin = new System.Windows.Forms.Padding(4);
            this.btnSaveProfile.Name = "btnSaveProfile";
            this.btnSaveProfile.Size = new System.Drawing.Size(132, 28);
            this.btnSaveProfile.TabIndex = 13;
            this.btnSaveProfile.Text = "Save as profile...";
            this.btnSaveProfile.UseVisualStyleBackColor = true;
            this.btnSaveProfile.Click += new System.EventHandler(this.btnSaveProfile_Click);
            // 
            // picModifier
            // 
            this.picModifier.Anchor = ((System.Windows.Forms.AnchorStyles)((System.Windows.Forms.AnchorStyles.Bottom | System.Windows.Forms.AnchorStyles.Right)));
            this.picModifier.Location = new System.Drawing.Point(491, 297);
            this.picModifier.Name = "picModifier";
            this.picModifier.Size = new System.Drawing.Size(25, 25);
            this.picModifier.TabIndex = 12;
            this.picModifier.TabStop = false;
            this.picModifier.Click += new System.EventHandler(this.picModifier_Click);
            // 
            // txtModifier
            // 
            this.txtModifier.Anchor = ((System.Windows.Forms.AnchorStyles)((System.Windows.Forms.AnchorStyles.Bottom | System.Windows.Forms.AnchorStyles.Right)));
            this.txtModifier.Location = new System.Drawing.Point(226, 298);
            this.txtModifier.Name = "txtModifier";
            this.txtModifier.Size = new System.Drawing.Size(259, 22);
            this.txtModifier.TabIndex = 11;
            this.txtModifier.TextChanged += new System.EventHandler(this.txtModifier_TextChanged);
            // 
            // cmbModifiers
            // 
            this.cmbModifiers.Anchor = ((System.Windows.Forms.AnchorStyles)((System.Windows.Forms.AnchorStyles.Bottom | System.Windows.Forms.AnchorStyles.Right)));
            this.cmbModifiers.DropDownStyle = System.Windows.Forms.ComboBoxStyle.DropDownList;
            this.cmbModifiers.FormattingEnabled = true;
            this.cmbModifiers.Location = new System.Drawing.Point(226, 265);
            this.cmbModifiers.Name = "cmbModifiers";
            this.cmbModifiers.Size = new System.Drawing.Size(290, 24);
            this.cmbModifiers.TabIndex = 10;
            this.cmbModifiers.SelectedIndexChanged += new System.EventHandler(this.cmbModifiers_SelectedIndexChanged);
            // 
            // lblModifiers
            // 
            this.lblModifiers.Anchor = ((System.Windows.Forms.AnchorStyles)((System.Windows.Forms.AnchorStyles.Bottom | System.Windows.Forms.AnchorStyles.Right)));
            this.lblModifiers.AutoSize = true;
            this.lblModifiers.Font = new System.Drawing.Font("Microsoft Sans Serif", 7.8F, System.Drawing.FontStyle.Bold, System.Drawing.GraphicsUnit.Point, ((byte)(0)));
            this.lblModifiers.Location = new System.Drawing.Point(223, 245);
            this.lblModifiers.Name = "lblModifiers";
            this.lblModifiers.Size = new System.Drawing.Size(79, 17);
            this.lblModifiers.TabIndex = 9;
            this.lblModifiers.Text = "Modifiers:";
            // 
            // txtDesc
            // 
            this.txtDesc.Anchor = ((System.Windows.Forms.AnchorStyles)(((System.Windows.Forms.AnchorStyles.Top | System.Windows.Forms.AnchorStyles.Bottom)
                        | System.Windows.Forms.AnchorStyles.Right)));
            this.txtDesc.Location = new System.Drawing.Point(226, 39);
            this.txtDesc.Multiline = true;
            this.txtDesc.Name = "txtDesc";
            this.txtDesc.ScrollBars = System.Windows.Forms.ScrollBars.Vertical;
            this.txtDesc.Size = new System.Drawing.Size(290, 190);
            this.txtDesc.TabIndex = 8;
            // 
            // label3
            // 
            this.label3.Anchor = ((System.Windows.Forms.AnchorStyles)((System.Windows.Forms.AnchorStyles.Top | System.Windows.Forms.AnchorStyles.Right)));
            this.label3.AutoSize = true;
            this.label3.Font = new System.Drawing.Font("Microsoft Sans Serif", 7.8F, System.Drawing.FontStyle.Bold, System.Drawing.GraphicsUnit.Point, ((byte)(0)));
            this.label3.Location = new System.Drawing.Point(223, 19);
            this.label3.Name = "label3";
            this.label3.Size = new System.Drawing.Size(95, 17);
            this.label3.TabIndex = 7;
            this.label3.Text = "Description:";
            // 
            // lstPatches
            // 
            this.lstPatches.Anchor = ((System.Windows.Forms.AnchorStyles)((((System.Windows.Forms.AnchorStyles.Top | System.Windows.Forms.AnchorStyles.Bottom)
                        | System.Windows.Forms.AnchorStyles.Left)
                        | System.Windows.Forms.AnchorStyles.Right)));
            this.lstPatches.CheckBoxes = true;
            this.lstPatches.Location = new System.Drawing.Point(11, 22);
            this.lstPatches.Name = "lstPatches";
            this.lstPatches.Size = new System.Drawing.Size(201, 360);
            this.lstPatches.TabIndex = 6;
            this.lstPatches.AfterSelect += new System.Windows.Forms.TreeViewEventHandler(this.lstPatches_AfterSelect);
            // 
            // btnApplyProfile
            // 
            this.btnApplyProfile.Anchor = System.Windows.Forms.AnchorStyles.Bottom;
            this.btnApplyProfile.Location = new System.Drawing.Point(326, 405);
            this.btnApplyProfile.Margin = new System.Windows.Forms.Padding(4);
            this.btnApplyProfile.Name = "btnApplyProfile";
            this.btnApplyProfile.Size = new System.Drawing.Size(132, 28);
            this.btnApplyProfile.TabIndex = 5;
            this.btnApplyProfile.Text = "Apply a profile...";
            this.btnApplyProfile.UseVisualStyleBackColor = true;
            this.btnApplyProfile.Click += new System.EventHandler(this.btnApplyProfile_Click);
            // 
            // btnApplyLast
            // 
            this.btnApplyLast.Anchor = System.Windows.Forms.AnchorStyles.Bottom;
            this.btnApplyLast.Location = new System.Drawing.Point(46, 405);
            this.btnApplyLast.Margin = new System.Windows.Forms.Padding(4);
            this.btnApplyLast.Name = "btnApplyLast";
            this.btnApplyLast.Size = new System.Drawing.Size(132, 28);
            this.btnApplyLast.TabIndex = 4;
            this.btnApplyLast.Text = "Apply last diffs";
            this.btnApplyLast.UseVisualStyleBackColor = true;
            this.btnApplyLast.Click += new System.EventHandler(this.btnApplyLast_Click);
            // 
            // btnSave
            // 
            this.btnSave.Anchor = System.Windows.Forms.AnchorStyles.Bottom;
            this.btnSave.Location = new System.Drawing.Point(186, 450);
            this.btnSave.Margin = new System.Windows.Forms.Padding(4);
            this.btnSave.Name = "btnSave";
            this.btnSave.Size = new System.Drawing.Size(132, 28);
            this.btnSave.TabIndex = 1;
            this.btnSave.Text = "Diff\'n\'Save!";
            this.btnSave.UseVisualStyleBackColor = true;
            this.btnSave.Click += new System.EventHandler(this.btnSave_Click);
            // 
            // mnuStrip
            // 
            this.mnuStrip.Items.AddRange(new System.Windows.Forms.ToolStripItem[] {
            this.mnuProfiles,
            this.aboutToolStripMenuItem});
            this.mnuStrip.LayoutStyle = System.Windows.Forms.ToolStripLayoutStyle.Flow;
            this.mnuStrip.Location = new System.Drawing.Point(0, 0);
            this.mnuStrip.Name = "mnuStrip";
            this.mnuStrip.Padding = new System.Windows.Forms.Padding(8, 2, 0, 2);
            this.mnuStrip.Size = new System.Drawing.Size(553, 28);
            this.mnuStrip.TabIndex = 2;
            this.mnuStrip.Text = "menuStrip1";
            // 
            // mnuProfiles
            // 
            this.mnuProfiles.Name = "mnuProfiles";
            this.mnuProfiles.Size = new System.Drawing.Size(70, 24);
            this.mnuProfiles.Text = "Profiles";
            this.mnuProfiles.Click += new System.EventHandler(this.mnuProfiles_Click);
            // 
            // imgListModifier
            // 
            this.imgListModifier.ImageStream = ((System.Windows.Forms.ImageListStreamer)(resources.GetObject("imgListModifier.ImageStream")));
            this.imgListModifier.TransparentColor = System.Drawing.Color.Transparent;
            this.imgListModifier.Images.SetKeyName(0, "green.png");
            this.imgListModifier.Images.SetKeyName(1, "red.png");
            // 
            // aboutToolStripMenuItem
            // 
            this.aboutToolStripMenuItem.Name = "aboutToolStripMenuItem";
            this.aboutToolStripMenuItem.Size = new System.Drawing.Size(62, 24);
            this.aboutToolStripMenuItem.Text = "About";
            this.aboutToolStripMenuItem.Click += new System.EventHandler(this.aboutToolStripMenuItem_Click);
            // 
            // frmMain
            // 
            this.AutoScaleDimensions = new System.Drawing.SizeF(8F, 16F);
            this.AutoScaleMode = System.Windows.Forms.AutoScaleMode.Font;
            this.ClientSize = new System.Drawing.Size(553, 680);
            this.Controls.Add(this.grpDiff);
            this.Controls.Add(this.groupBox1);
            this.Controls.Add(this.mnuStrip);
            this.MainMenuStrip = this.mnuStrip;
            this.Margin = new System.Windows.Forms.Padding(4);
            this.Name = "frmMain";
            this.Text = "xDiffPatcher by LightFighter - DiffTeam!";
            this.groupBox1.ResumeLayout(false);
            this.groupBox1.PerformLayout();
            this.grpDiff.ResumeLayout(false);
            this.grpDiff.PerformLayout();
            ((System.ComponentModel.ISupportInitialize)(this.picModifier)).EndInit();
            this.mnuStrip.ResumeLayout(false);
            this.mnuStrip.PerformLayout();
            this.ResumeLayout(false);
            this.PerformLayout();

        }

        #endregion

        private System.Windows.Forms.GroupBox groupBox1;
        private System.Windows.Forms.Button btnLoad;
        private System.Windows.Forms.Button btnOpenDiff;
        private System.Windows.Forms.TextBox txtDiffFile;
        private System.Windows.Forms.Button btnOpenExe;
        private System.Windows.Forms.Label label2;
        private System.Windows.Forms.TextBox txtExeFile;
        private System.Windows.Forms.Label label1;
        private System.Windows.Forms.GroupBox grpDiff;
        private System.Windows.Forms.Button btnSave;
        private System.Windows.Forms.Button btnApplyProfile;
        private System.Windows.Forms.Button btnApplyLast;
        private System.Windows.Forms.MenuStrip mnuStrip;
        private System.Windows.Forms.ToolStripMenuItem mnuProfiles;
        private System.Windows.Forms.Label label3;
        private System.Windows.Forms.TextBox txtDesc;
        private System.Windows.Forms.PictureBox picModifier;
        private System.Windows.Forms.TextBox txtModifier;
        private System.Windows.Forms.ComboBox cmbModifiers;
        private System.Windows.Forms.Label lblModifiers;
        private System.Windows.Forms.ImageList imgListModifier;
        private System.Windows.Forms.Button btnSaveProfile;
        public PatchList lstPatches;
        private System.Windows.Forms.Label lblModifierInfo;
        private System.Windows.Forms.ToolStripMenuItem aboutToolStripMenuItem;
    }
}

